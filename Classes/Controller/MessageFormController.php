<?php
declare(strict_types=1);
namespace Cylancer\CySendMails\Controller;

use Cylancer\CySendMails\Domain\Repository\FrontendUserRepository;
use Cylancer\CySendMails\Domain\Repository\MessageRepository;
use Cylancer\CySendMails\Domain\Repository\FrontendUserGroupRepository;
use Cylancer\CySendMails\Domain\Model\ValidationResults;
use Cylancer\CySendMails\Domain\Model\Message;
use Cylancer\CySendMails\Domain\Model\FrontendUserGroup;
use Cylancer\CySendMails\Domain\Model\FrontendUser;
use Cylancer\CySendMails\Service\FormSessionKeyHandlerService;
use Cylancer\CySendMails\Service\FrontendUserService;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Utility\MailUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2024 C. Gogolin <service@cylancer.net>
 *
 * @package Cylancer\CySendMails\Controller
 */
class MessageFormController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    const EXTENSION_NAME = 'CySendMails';

    const TX_EXTENSION_NAME = 'tx_cy_send_mails';

    const MAIL_TEMPLATE = 'MessageMail';

    const FRONTEND_USER_STORAGE_UIDS = 'frontendUserStorageUids';

    const MESSAGES_STORAGE_UID = 'messagesStorageUid';

    const GROUP_MARKER = '#';

    const EXCLUDE_MARKER = '- ';

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

    /** @var FormSessionKeyHandlerService */
    private $formSessionKeyHandlerService;

    /** @var array */
    private $allowedReceivers;

    /** @var array */
    private $allowedReceiverGroups;

    /**
     *
     * @param FrontendUserRepository $frontendUserRepository
     * @param MessageRepository $messageRepository
     * @param FrontendUserGroupRepository $frontendUserGroupRepository
     * @param FormSessionKeyHandlerService $formSessionKeyHandlerService
     */
    public function __construct(
        FrontendUserRepository $frontendUserRepository,
        MessageRepository $messageRepository,
        FrontendUserGroupRepository $frontendUserGroupRepository,
        FormSessionKeyHandlerService $formSessionKeyHandlerService
    ) {
        $this->messageRepository = $messageRepository;
        $this->frontendUserRepository = $frontendUserRepository;
        $this->frontendUserGroupRepository = $frontendUserGroupRepository;
        $this->formSessionKeyHandlerService = $formSessionKeyHandlerService;
    }

    protected function initializeAction()
    {
        $this->frontendUserRepository->setStorageUids(GeneralUtility::intExplode(',', $this->settings[MessageFormController::FRONTEND_USER_STORAGE_UIDS]));
        $this->messageRepository->setStorageUids(GeneralUtility::intExplode(',', $this->settings[MessageFormController::MESSAGES_STORAGE_UID]));
        $this->frontendUserService = new FrontendUserService($this->frontendUserRepository);
        /** @var FrontendUser $frontendUser  */
        $this->allowedReceivers = array_filter(
            $this->frontendUserRepository->findAll()->toArray(),
            //
            function ($frontendUser) {
                return !empty($frontendUser->getEmail());
            }
        );
        // debug($this->allowedReceivers);

        $tmp = [];
        foreach ($this->frontendUserService->getAllGroups($this->frontendUserService->getCurrentUser()) as $frontendUserGroup) {
            foreach ($frontendUserGroup->getReceiverGroup() as $receiverGroup) {
                if (!empty($receiverGroup->getReceiverGroupName())) {
                    $tmp[$receiverGroup->getUid()] = $receiverGroup;
                }
            }
        }
        $this->allowedReceiverGroups = array_values($tmp);
        // debug($this->allowedReceiverGroups);
    }

    public function showAction(): ResponseInterface
    {

        if ($this->request->hasArgument(MessageFormController::MESSAGE_KEY)) {
            $msg = $this->request->getArgument(MessageFormController::MESSAGE_KEY);
        } else {
            $msg = new Message();
            $msg->setSender($this->frontendUserService->getCurrentUser());
            $msg->setKey($this->formSessionKeyHandlerService->createSessionFormKey($this->request, MessageFormController::TX_EXTENSION_NAME));
        }

        $validationResults = $this->request->hasArgument(MessageFormController::VALIDATION_RESULTS_KEY) ? $this->request->getArgument(MessageFormController::VALIDATION_RESULTS_KEY) : new ValidationResults();

        /** @var FrontendUser $frontendUser  */
        /** @var FrontendUserGroup $frontendUserGroup  */
        $receivers = array_merge(
            array_map(function ($frontendUser) {
                return "'" . $frontendUser->getName() . "'";
            }, $this->allowedReceivers),
            array_map(function ($frontendUserGroup) {
                return !empty($frontendUserGroup->getReceiverGroupName()) ? ("'" . MessageFormController::GROUP_MARKER . $frontendUserGroup->getReceiverGroupName() . "'") : '';
            }, $this->allowedReceiverGroups)
        );

        asort($receivers);

        $excludedReceivers = array_map(function ($frontendUser) {
            return "'" . MessageFormController::EXCLUDE_MARKER . $frontendUser->getName() . "'";
        }, $this->allowedReceivers); //
        asort($excludedReceivers);

        $receivers = array_merge($receivers, $excludedReceivers);

        $this->view->assign('message', $msg);
        $this->view->assign('receivers', implode(',', $receivers));
        $this->view->assign('validationResults', $validationResults);
        $this->view->assign('footer', $this->settings['formFooter']);
        $this->view->assign('header', $this->settings['formHeader']);


        return $this->htmlResponse();
    }

    private function getValidReceiverGroup(string $name): ?FrontendUserGroup
    {
        /** @var FrontendUserGroup $frontendUserGroup  */
        foreach ($this->allowedReceiverGroups as $frontendUserGroup) {
            if ($frontendUserGroup->getReceiverGroupName() === $name) {
                return $frontendUserGroup;
            }
        }
        return null;
    }

    private function getValidReceiver(string $name): ?FrontendUser
    {
        /** @var FrontendUser $frontendUser  */
        foreach ($this->allowedReceivers as $frontendUser) {
            if ($frontendUser->getName() === $name) {
                return $frontendUser;
            }
        }
        return null;
    }

    public function sendAction(Message $message)
    {

        /** @var FrontendUser $frontendUser  */
        /** @var FrontendUserGroup $frontendUserGroup  */

        // Preparing :: idetifier the receivers:
        $receiversSource = explode(',', $message->getReceivers());
        $receiverGroups = [];
        $receivers = [];
        $excludedReceivers = [];

        $wrongReceiverGroups = [];
        $wrongReceivers = [];

        // collect the receiver, the receiver groups and the excluded receivers

        /** @var array $tmp  */
        foreach ($receiversSource as $receiver) {
            $receiver = trim($receiver);
            if (substr($receiver, 0, 1) === MessageFormController::GROUP_MARKER) {
                $tmp = $this->getValidReceiverGroup(substr($receiver, 1));
                if ($tmp != null) {
                    $receiverGroups[] = $tmp;
                } else {
                    $wrongReceiverGroups[] = substr($receiver, 1);
                }
            } else if (substr($receiver, 0, 2) === MessageFormController::EXCLUDE_MARKER) {
                $tmp = $this->getValidReceiver(substr($receiver, 2));
                if ($tmp != null) {
                    $excludedReceivers[$tmp->getUid()] = $tmp;
                } else {
                    if (!empty(trim($receiver))) {
                        $wrongReceivers[] = $receiver;
                    }
                }
            } else if (substr($receiver, 0, 2) !== MessageFormController::EXCLUDE_MARKER) {
                $tmp = $this->getValidReceiver($receiver);
                if ($tmp != null) {
                    $receivers[$tmp->getUid()] = $tmp;
                } else {
                    if (!empty(trim($receiver))) {
                        $wrongReceivers[] = $receiver;
                    }
                }
            }
        }

        /** @var ValidationResults $validationResults */
        $validationResults = $this->validate($message, $wrongReceivers, $wrongReceiverGroups);
        if (!$validationResults->hasErrors()) {

            /** @var FrontendUserGroup $frontendUserGroup  */
            /** @var FrontendUserGroup $receiverGroup  */
            /** @var FrontendUser $frontendUser */
            /** @var FrontendUserGroup $frontendUserGroup  */
            foreach ($receiverGroups as $receiverGroup) {
                foreach ($this->frontendUserRepository->findAll() as $frontendUser) {
                    if (
                        in_array(
                            $receiverGroup->getUid(),
                            array_map(function ($frontendUserGroup) {
                                return $frontendUserGroup->getUid();
                            }, $this->frontendUserService->getAllGroups($frontendUser))
                        )
                    ) {
                        if (!key_exists($frontendUser->getUid(), $receivers) && !empty($frontendUser->getEmail())) {
                            $receivers[$frontendUser->getUid()] = $frontendUser;
                        }
                    }
                }
            }
            // removes the excluded receivers
            /** @var int $uid */
            foreach (array_keys($excludedReceivers) as $uid) {
                unset($receivers[$uid]);
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
                    $minimalMessage->setPid(GeneralUtility::intExplode(',', $this->settings[MessageFormController::MESSAGES_STORAGE_UID])[0]);
                    $minimalMessage->setSender($message->getSender());
                    $minimalMessage->setSubject(substr($message->getSubject(), 0, 15));
                    $minimalMessage->setAttachmentsMetaData('count:' . count($message->getAttachments()));
                    $minimalMessage->setMessage('-');
                    $minimalMessage->setReceivers($message->getReceivers());
                    $this->messageRepository->add($minimalMessage);
                  //  debug($minimalMessage, 'minimalMessage');
                    break;
                case 'full':
                    $message->setPid(GeneralUtility::intExplode(',', $this->settings[MessageFormController::MESSAGES_STORAGE_UID])[0]);
                    $this->messageRepository->add($message);
                    break;
            }

            $sender = new Address(MailUtility::getSystemFromAddress(), $message->getSender()->getName());

            if ($message->getCopyToSender()) {
                $receivers[$currentFrontendUser->getUid()] = $currentFrontendUser;
            }

            $receiverListing = implode(', ', array_map(function ($frontendUser) {
                return $frontendUser->getName();
            }, $receivers));
            $receiverGroupListing = implode(', ', array_map(function ($frontendUserGroup) {
                return $frontendUserGroup->getReceiverGroupName();
            }, $receiverGroups));

            $subject = html_entity_decode($this->settings['subjectPrefix']) . $message->getSubject();

            $replyTo = null;
            if ($message->getSendSenderAddress()) {
                if (!empty($currentFrontendUser->getEmail())) {
                    $replyTo = new Address($currentFrontendUser->getEmail(), $currentFrontendUser->getName());
                }
            } else {
                if (!empty($this->settings['noReplySenderEmail'])) {
                    $replyTo = new Address($this->settings['noReplySenderEmail'], LocalizationUtility::translate('message.sender.noReply', 'CySendMails'));
                }
            }

            $this->formSessionKeyHandlerService->removeSessionFormKey($this->request, MessageFormController::TX_EXTENSION_NAME, $message->getKey());

            if (!isset($this->settings['simulation']) || $this->settings['simulation'] != 1) {

                $fluidEmail = GeneralUtility::makeInstance(FluidEmail::class);
                $fluidEmail

                    ->from($sender)
                    ->subject($subject)
                    ->format(FluidEmail::FORMAT_BOTH) // send HTML and plaintext mail
                    ->setTemplate(MessageFormController::MAIL_TEMPLATE)
                    ->assign('message', str_replace("\n", '', $message->getMessage()))
                    //                    ->assign('footer', str_replace('&#039;', "'", $this->settings['emailFooter']))
                    ->assign('receiverListing', $receiverListing)
                    ->assign('receiverGroupListing', $receiverGroupListing)
                    ->assign('footer', $this->settings['emailFooter'])
                ;
                if ($replyTo !== null) {
                    $fluidEmail->replyTo($replyTo);
                }

                foreach ($message->getAttachments() as $attachment) {
                    $fluidEmail->attachFromPath($attachment['tmp_name'], $attachment['name']);
                }

                foreach ($receivers as $receiver) {
                    $fluidEmail->addBcc(new Address($receiver->getEmail(), $receiver->getFirstName() . ' ' . $receiver->getLastName()));
                }

                try {
                    GeneralUtility::makeInstance(MailerInterface::class)->send($fluidEmail);
                    $successful = true;
                } catch (\Exception $e) {
                    $successful = false;
                }
            } else {
                $successful = true;
            }

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
            return $this->htmlResponse();
        } else {
            return GeneralUtility::makeInstance(ForwardResponse::class, 'show')->withArguments([
                MessageFormController::VALIDATION_RESULTS_KEY => $validationResults,
                MessageFormController::MESSAGE_KEY => $message
            ]);
        }
    }

    private function validate(Message $message, array $wrongReceivers = [], array $wrongReceiverGroups = []): ValidationResults
    {

        /** @var ValidationResults $validationResults */
        $validationResults = new ValidationResults();
        if (!$this->formSessionKeyHandlerService->existsSessionFormKey($this->request, MessageFormController::TX_EXTENSION_NAME, $message->getKey())) {
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
      //  debug($message);

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
            $i++;
        }
        return round($bytes, $precision) . ' ' . $units[$i];
    }

}