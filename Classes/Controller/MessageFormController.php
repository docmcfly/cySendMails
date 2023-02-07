<?php
declare(strict_types = 1);
namespace Cylancer\CySendMails\Controller;

use Cylancer\CySendMails\Domain\Repository\FrontendUserRepository;
use Cylancer\CySendMails\Domain\Repository\MessageRepository;
use Cylancer\CySendMails\Domain\Repository\FrontendUserGroupRepository;
use Cylancer\CySendMails\Domain\Model\ValidationResults;
use Cylancer\CySendMails\Domain\Model\Message;
use Cylancer\CySendMails\Domain\Model\FrontendUserGroup;
use Cylancer\CySendMails\Domain\Model\FrontendUser;
use Cylancer\CySendMails\Service\EmailSendService;
use Cylancer\CySendMails\Service\FrontendUserService;
use TYPO3\CMS\Core\Session\UserSession;
use TYPO3\CMS\Core\Session\UserSessionManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023 C. Gogolin <service@cylancer.net>
 *
 * @package Cylancer\CySendMails\Controller
 */
class MessageFormController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    const EXTENSION_NAME = 'CySendMails';

    const TX_EXTENSION_NAME = 'tx_cy_send_mails';

    const MAIL_TEMPLATE = 'MessageMail';

    const GROUP_MARKER = '#';

    const EXCLUDE_MARKER = '-';

    const VALIDATION_RESULTS_KEY = 'validationResults';

    const MESSAGE_KEY = 'message';

    /** @var MessageRepository   */
    private $messageRepository;

    /** @var FrontendUserRepository  */
    private $frontendUserRepository;

    /** @var FrontendUserGroupRepository     */
    private $frontendUserGroupRepository;

    /** @var FrontendUserService      */
    private $frontendUserService;

    /**  @var EmailSendService      */
    private $emailSendService;

    /**
     *
     * @param FrontendUserRepository $frontendUserRepository
     * @param MessageRepository $messageRepository
     * @param FrontendUserService $frontendUserService
     * @param FrontendUserGroupRepository $frontendUserGroupRepository
     * @param EmailSendService $emailSendService
     */
    public function __construct(FrontendUserRepository $frontendUserRepository, MessageRepository $messageRepository, FrontendUserService $frontendUserService, //
    FrontendUserGroupRepository $frontendUserGroupRepository, EmailSendService $emailSendService)
    {
        $this->messageRepository = $messageRepository;
        $this->frontendUserRepository = $frontendUserRepository;
        $this->frontendUserService = $frontendUserService;
        $this->frontendUserGroupRepository = $frontendUserGroupRepository;
        $this->emailSendService = $emailSendService;
    }

    private function getAllowedReceivers()
    {
        /** @var FrontendUser $frontendUser  */
        return array_filter($this->frontendUserRepository->findAll()->toArray(), //
        function ($frontendUser) {
            return ! empty($frontendUser->getEmail());
        });
    }

    private function getAllowedReceiverGroups()
    {
        /** @var FrontendUserGroup $frontendUserGroup  */
        /** @var FrontendUserGroup $receiverGroup  */
        $return = [];
        foreach ($this->frontendUserService->getAllGroups($this->frontendUserService->getCurrentUser()) as $frontendUserGroup) {
            foreach ($frontendUserGroup->getReceiverGroup() as $receiverGroup) {
                if (! empty($receiverGroup->getReceiverGroupName())) {
                    $return[$receiverGroup->getUid()] = $receiverGroup;
                }
            }
        }
        return array_values($return);
    }

    public function showAction(): void
    {
        if ($this->request->hasArgument(MessageFormController::MESSAGE_KEY)) {
            $msg = $this->request->getArgument(MessageFormController::MESSAGE_KEY);
        } else {
            $msg = new Message();
            $msg->setSender($this->frontendUserService->getCurrentUser());
            $msg->setKey($this->createSessionMessageKey());
        }
        $validationResults = $this->request->hasArgument(MessageFormController::VALIDATION_RESULTS_KEY) ? $this->request->getArgument(MessageFormController::VALIDATION_RESULTS_KEY) : new ValidationResults();

        /** @var FrontendUser $frontendUser  */
        /** @var FrontendUserGroup $frontendUserGroup  */
        $receivers = array_merge( //

        array_map(function ($frontendUser) {
            return "'" . $frontendUser->getName() . "'";
        }, $this->getAllowedReceivers()), //
        array_map(function ($frontendUser) {
            return "'" . MessageFormController::EXCLUDE_MARKER . $frontendUser->getName() . "'";
        }, $this->getAllowedReceivers()), //
        array_map(function ($frontendUserGroup) {
            return ! empty($frontendUserGroup->getReceiverGroupName()) ? ("'" . MessageFormController::GROUP_MARKER . $frontendUserGroup->getReceiverGroupName() . "'") : '';
        }, $this->getAllowedReceiverGroups()));

        asort($receivers);
        
    //     debug($receivers);
        
        $this->view->assign('message', $msg);
        $this->view->assign('receivers', implode(',', $receivers));
        $this->view->assign('validationResults', $validationResults);
        $this->view->assign('footer', $this->settings['formFooter']);
        $this->view->assign('header', $this->settings['formHeader']);
    }

    public function sendAction(Message $message)
    {

        /** @var FrontendUser $frontendUser  */
        /** @var FrontendUserGroup $frontendUserGroup  */
        $allowedReceiverUids = array_map(function ($frontendUser) {
            return $frontendUser->getUid();
        }, $this->getAllowedReceivers());
        $allowedReceiverGroupUids = array_map(function ($frontendUserGroup) {
            return $frontendUserGroup->getUid();
        }, $this->getAllowedReceiverGroups());

        // Preparing :: idetifier the receivers:
        $receiversSource = explode(',', $message->getReceivers());
        $receiverGroups = [];
        $receivers = [];

        $wrongReceiverGroups = [];
        $wrongReceivers = [];

        // collect all receivers
        /** @var array $tmp  */
        foreach ($receiversSource as $receiver) {
            $receiver = trim($receiver);
            if (substr($receiver, 0, 1) === MessageFormController::GROUP_MARKER) {
                $tmp = $this->frontendUserGroupRepository->findByReceiverGroupName(substr($receiver, 1));
                if (count($tmp) == 1 && in_array($tmp[0]->getUid(), $allowedReceiverGroupUids)) {
                    $receiverGroups[] = $tmp[0];
                } else {
                    $wrongReceiverGroups[] = substr($receiver, 1);
                }
            } else  if (substr($receiver, 0, 1) !== MessageFormController::EXCLUDE_MARKER) {
                $tmp = $this->frontendUserRepository->findByName($receiver);
                if (count($tmp) == 1 && in_array($tmp[0]->getUid(), $allowedReceiverUids)) {
                    $tmp = $tmp[0];
                    $receivers[$tmp->getUid()] = $tmp;
                } else {
                    if (! empty(trim($receiver))) {
                        $wrongReceivers[] = $receiver;
                    }
                }
            }
        }
        
        // Removes the excluded recipients
        /** @var array $tmp  */
        foreach ($receiversSource as $receiver) {
            $receiver = trim($receiver);
            if (substr($receiver, 0, 1) === MessageFormController::EXCLUDE_MARKER) {
                $tmp = $this->frontendUserRepository->findByName($receiver);
                if (count($tmp) == 1 && in_array($tmp[0]->getUid(), $allowedReceiverUids)) {
                    $tmp = $tmp[0];
                    unset($receivers[$tmp->getUid()]);
                } else {
                    if (! empty(trim($receiver))) {
                        $wrongReceivers[] = $receiver;
                    }
                }
            }
        }
        
        /** @var ValidationResults $validationResults */
        $validationResults = $this->validate($message, $wrongReceivers, $wrongReceiverGroups, $wrongAttachments);
        if (! $validationResults->hasErrors()) {

            /** @var FrontendUserGroup $frontendUserGroup  */
            /** @var FrontendUserGroup $receiverGroup  */
            /** @var FrontendUser $frontendUser */
            /** @var FrontendUserGroup $frontendUserGroup  */
            foreach ($receiverGroups as $receiverGroup) {
                foreach ($this->frontendUserRepository->findAll() as $frontendUser) {
                    if (in_array($receiverGroup->getUid(), array_map(function ($frontendUserGroup) {
                        return $frontendUserGroup->getUid();
                    }, $this->frontendUserService->getAllGroups($frontendUser)))) {
                        $tmp = $receivers[$frontendUser->getUid()];
                        if ($tmp == null && ! empty($frontendUser->getEmail())) {
                            $receivers[$frontendUser->getUid()] = $frontendUser;
                        }
                    }
                }
            }

            /** @var FrontendUser $currentFrontendUser  */
            $currentFrontendUser = $this->frontendUserService->getCurrentUser();
            $message->setSender($currentFrontendUser);
            $message->setAttachmentsMetaData(var_export($message->getAttachments(), true));
            $attachments = array_filter($message->getAttachments(), function ($v) {
                return $v['error'] === UPLOAD_ERR_OK;
            });
            $message->setAttachments($attachments);

            switch ($this->settings['saveMessages']) {
                case 'none':
                    break;
                case 'minimal':
                    $minimalMessage = new Message();
                    $minimalMessage->setSender($message->getSender());
                    $minimalMessage->setSubject(substr($message->getSubject(), 0, 15));
                    $minimalMessage->setAttachmentsMetaData('count:' . count($message->getAttachments()));
                    $minimalMessage->setMessage('-');
                    $minimalMessage->setReceivers($message->getReceivers());
                    $this->messageRepository->add($minimalMessage);
                    break;
                case 'full':
                    $this->messageRepository->add($message);
                    break;
            }

            $sender = [
                \TYPO3\CMS\Core\Utility\MailUtility::getSystemFromAddress() => $message->getSender()->getName()
            ];

            if ($message->getCopyToSender()) {
                $receivers[$currentFrontendUser->getUid()] = $currentFrontendUser;
            }

            $receiverListing = implode(', ', array_map(function ($frontendUser) {
                return $frontendUser->getName();
            }, $receivers));
            $receiverGroupListing = implode(', ', array_map(function ($frontendUserGroup) {
                return $frontendUserGroup->getReceiverGroupName();
            }, $receiverGroups));

            $content = [
                'message' => str_replace("\n", '', $message->getMessage()),
                'messageText' => str_replace('&#039;', "'", html_entity_decode(strip_tags($message->getMessage()), ENT_QUOTES)),
                'footer' => str_replace('&#039;', "'", $this->settings['emailFooter']),
                'footerText' => str_replace('&#039;', "'", html_entity_decode(strip_tags($this->settings['emailFooter']), ENT_QUOTES)),
                'receiverListing' => $receiverListing,
                'receiverGroupListing' => $receiverGroupListing
            ];
            $subject = html_entity_decode($this->settings['subjectPrefix']) . $message->getSubject();

            $replyTo = [];
            if ($message->getSendSenderAddress()) {
                if (! empty($currentFrontendUser->getEmail())) {
                    $replyTo[$currentFrontendUser->getEmail()] = $currentFrontendUser->getName();
                }
            } else {
                if (! empty($this->settings['noReplySenderEmail'])) {
                    $replyTo[$this->settings['noReplySenderEmail']] = LocalizationUtility::translate('message.sender.noReply', 'CySendMails');
                }
            }

            $successful = count($receivers) > 0;
            foreach ($receivers as $receiver) {
                $recipient[] = $receiver->getFirstName() . ' ' . $receiver->getLastName() . ' <' . $receiver->getEmail() . '>';
                // $receiver->getEmail() => $receiver->getFirstName() . ' ' . $receiver->getLastName()
                // ];
            }

            $this->removeSessionMessageKey($message->getKey());

            $successful &= $this->emailSendService->sendTemplateEmail($recipient, $sender, $replyTo, $subject, MessageFormController::MAIL_TEMPLATE, MessageFormController::EXTENSION_NAME, $content, $message->getAttachments());

            $attachmentListing = [];
            foreach ($attachments as $attachment) {
                $attachmentListing[] = $attachment['name'];
            }

            $this->view->assign('message', $message);
            $this->view->assign('receivers', $receiverListing);
            $this->view->assign('receiverGroups', $receiverGroupListing);
            $this->view->assign('attachments', $attachmentListing);
            if ($successful) {
                $this->view->assign('footer', $this->settings['successfulSendFooter']);
                $this->view->assign('header', $this->settings['successfulSendHeader']);
            } else {
                $this->view->assign('footer', $this->settings['failedSendFooter']);
                $this->view->assign('header', $this->settings['failedSendHeader']);
            }
        } else {
            $this->forward('show', null, null, [
                MessageFormController::VALIDATION_RESULTS_KEY => $validationResults,
                MessageFormController::MESSAGE_KEY => $message
            ]);
        }
        // $this->redirect("show");
    }

    private function validate(Message $message, array $wrongReceivers = [], array $wrongReceiverGroups = []): ValidationResults
    {

        /** @var ValidationResults $validationResults */
        $validationResults = new ValidationResults();
        if (! $this->existsSessionMessageKey($message->getKey())) {
            $validationResults->addError('receivers.messageKeyInvalid');
        }
        if (strlen(trim($message->getReceivers())) == 0) {
            $validationResults->addError('receivers.isEmpty');
        }
        if (strlen(trim($message->getSubject())) == 0) {
            $validationResults->addError('subject.isEmpty');
        }
        if (strlen(trim($message->getMessage())) == 0) {
            $validationResults->addError('message.isEmpty');
        }
        if (count($wrongReceiverGroups) > 0) {
            $validationResults->addError('receivers.wrongReceiverGroups', [
                implode(', ', $wrongReceiverGroups)
            ]);
        }
        if (count($wrongReceivers) > 0) {
            $validationResults->addError('receivers.wrongReceivers', [
                implode(', ', $wrongReceivers)
            ]);
        }

        $attachmentSize = 0;
        foreach ($message->getAttachments() as $attachment) {
            $attachmentSize += $attachment['size'];
        }

        if ($attachmentSize > intval($this->settings['maxAttachmentsSize'])) {
            $validationResults->addError('attachments.tooLarge', [
                $this->formatBytes(intval($this->settings['maxAttachmentsSize']))
            ]);
        }

        $wrongAttachments = array_filter($message->getAttachments(), function ($v) {
            return $v['error'] != UPLOAD_ERR_OK && $v['error'] != UPLOAD_ERR_NO_FILE;
        });

        if (count($wrongAttachments) > 0) {
            $tmp = [];
            foreach ($wrongAttachments as $wrongAttachment) {
                $tmp[] = $wrongAttachment['name'];
            }
            $validationResults->addError('attachments.wrongAttachments', [
                implode(', ', $tmp)
            ]);
        }

        return $validationResults;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = [
            'Byte',
            'KB',
            'MB',
            'GB',
            'TB'
        ];
        $i = 0;

        while ($bytes > 1024) {
            $bytes /= 1024;
            $i ++;
        }
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    private function getUserSession(): UserSession
    {
        if (isset($_COOKIE['fe_typo_user'])) {
            $userSessionManagement = UserSessionManager::create('FE');
            return $userSessionManagement->createFromGlobalCookieOrAnonymous('fe_typo_user');
        } else {
            throw new \Exception('User has not a frontend user session.');
        }
    }

    private function updateUserSession(UserSession $userSession): void
    {
        if (isset($_COOKIE['fe_typo_user'])) {
            UserSessionManager::create('FE')->updateSession($userSession);
        } else {
            throw new \Exception('User has not a frontend user session.');
        }
    }

    private function createSessionMessageKey(): String
    {
        $messageKey = md5(uniqid(strval(rand()), true));
        $this->setSessionMessageKey($messageKey);
        return $messageKey;
    }

    private function existsSessionMessageKey(String $messageKey): bool
    {
        $userSession = $this->getUserSession();
        if (! $userSession->hasData()) {
            return false;
        }
        $data = $userSession->getData();
        if (! key_exists(MessageFormController::TX_EXTENSION_NAME, $data)) {
            return false;
        }
        $messageKeys = $data[MessageFormController::TX_EXTENSION_NAME];
        return key_exists($messageKey, $messageKeys);
    }

    private function removeSessionMessageKey(String $messageKey): void
    {
        $userSession = $this->getUserSession();
        if (! $userSession->hasData()) {
            throw new \Exception('User session data does not exist.');
        }
        $data = $userSession->getData();
        if (! key_exists(MessageFormController::TX_EXTENSION_NAME, $data)) {
            throw new \Exception('User session data does not contain message keys.');
        }
        $messageKeys = $data[MessageFormController::TX_EXTENSION_NAME];
        if (! key_exists($messageKey, $messageKeys)) {
            throw new \Exception('User session data does not contain the message key: ' . $messageKey);
        }
        unset($messageKeys[$messageKey]);
        $data[MessageFormController::TX_EXTENSION_NAME] = $messageKeys;
        $userSession->overrideData($data);
        $this->updateUserSession($userSession);
    }

    private function setSessionMessageKey(String $messageKey): void
    {
        $userSession = $this->getUserSession();
        $data = $userSession->hasData() ? $userSession->getData() : [];
        $messageKeys = key_exists(MessageFormController::TX_EXTENSION_NAME, $data) ? $data[MessageFormController::TX_EXTENSION_NAME] : [];
        $messageKeys[$messageKey] = $messageKey;
        $data[MessageFormController::TX_EXTENSION_NAME] = $messageKeys;
        $userSession->overrideData($data);
        $this->updateUserSession($userSession);
    }
}