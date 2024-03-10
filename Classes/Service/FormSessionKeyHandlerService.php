<?php
namespace Cylancer\CySendMails\Service;

use Cylancer\CySendMails\Controller\MessageFormController;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Session\UserSession;
use TYPO3\CMS\Core\Session\UserSessionManager;
use TYPO3\CMS\Core\SingletonInterface;

/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2024 C. Gogolin <service@cylancer.net>
 *
 * @package Cylancer\CySendMails\Domain\Service
 *         
 */
class FormSessionKeyHandlerService implements SingletonInterface
{

    /**
     * Creates an form key and store the key in the frontend user session. 
     * You can add this key in your form object. 
     * 
     * @param string $extensionKey
     * @return string
     */
    public function createSessionFormKey(ServerRequestInterface $request, string $extensionKey): string
    {
        $formKey = md5(uniqid(strval(rand()), true));
        $this->setSessionFormKey($request, $extensionKey, $formKey);
        return $formKey;
    }

    /**
     * Returns true if the specified key in the frontend user session. 
     *  
     * @param string $extensionKey
     * @param string $formKey
     * @return bool 
     */
    public function existsSessionFormKey(ServerRequestInterface $request, string $extensionKey, string $formKey): bool
    {
        $userSession = $this->getUserSession($request);
        if (!$userSession->hasData()) {
            return false;
        }
        $data = $userSession->getData();
        if (!key_exists($extensionKey, $data)) {
            return false;
        }
        $messageKeys = $data[$extensionKey];
        return key_exists($formKey, $messageKeys);
    }

    /**
     * Removes the specified form key from the frontend user session. 
     * 
     * @param string $extensionKey
     * @param string $formKey
     * @return void 
     */
    public function removeSessionFormKey(ServerRequestInterface $request, string $extensionKey, string $formKey): void
    {
        $userSession = $this->getUserSession($request);
        if (!$userSession->hasData()) {
            throw new \Exception('User session data does not exist.');
        }
        $data = $userSession->getData();
        if (!key_exists($extensionKey, $data)) {
            throw new \Exception('User session data does not contain message keys.');
        }
        $formKeys = $data[$extensionKey];
        if (!key_exists($formKey, $formKeys)) {
            throw new \Exception('User session data does not contain the message key: ' . $formKey);
        }
        unset($formKeys[$formKey]);
        $data[MessageFormController::TX_EXTENSION_NAME] = $formKeys;
        $userSession->overrideData($data);
        $this->updateUserSession($userSession);
    }

    private function getUserSession(ServerRequestInterface $request): UserSession
    {
        if (isset($_COOKIE['fe_typo_user'])) {
            $userSessionManagement = UserSessionManager::create('FE');
            return $userSessionManagement->createFromRequestOrAnonymous($request, 'fe_typo_user');
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

    private function setSessionFormKey(ServerRequestInterface $request, string $extensionKey, string $formKey): void
    {
        $userSession = $this->getUserSession($request);
        $data = $userSession->hasData() ? $userSession->getData() : [];
        $formKeys = key_exists($extensionKey, $data) ? $data[$extensionKey] : [];
        $formKeys[$formKey] = $formKey;
        $data[$extensionKey] = $formKeys;
        $userSession->overrideData($data);
        $this->updateUserSession($userSession);
    }




}