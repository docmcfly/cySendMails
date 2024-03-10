<?php
declare(strict_types = 1);
namespace Cylancer\CySendMails\Service;

use TYPO3\CMS\Core\SingletonInterface;
use Cylancer\CySendMails\Domain\Repository\FrontendUserRepository;
use Cylancer\CySendMails\Domain\Model\FrontendUserGroup;
use Cylancer\CySendMails\Domain\Model\FrontendUser;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;

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
class FrontendUserService implements SingletonInterface
{

    /**
     *
     * @var FrontendUserRepository
     */
    public $frontendUserRepository = null;

    public function __construct(FrontendUserRepository $frontendUserRepository)
    {
        $this->frontendUserRepository = $frontendUserRepository;
    }
    
    /**
     *
     * @return FrontendUser Returns the current frontend user
     */
    public function getCurrentUser(): ?FrontendUser
    {
        if (! $this->isLogged()) {
            return null;
        }
        return $this->frontendUserRepository->findByUid(FrontendUserService::getCurrentUserUid());
    }

    public function getContext(): Context
    {
        return GeneralUtility::makeInstance(Context::class);
    }

    /**
     * Returns the current frontend user uid.
     *
     * @return int
     */
    public function getCurrentUserUid(): ?int
    {
        if (! $this->isLogged()) {
            return null;
        }
        return $this->getContext()->getPropertyFromAspect('frontend.user', 'id');
    }

    /**
     * Check if the user is logged
     *
     * @return bool
     */
    public function isLogged(): bool
    {
        if ($this->getContext()->getPropertyFromAspect('frontend.user', 'isLoggedIn')) {
            $userUid = $this->getContext()->getPropertyFromAspect('frontend.user', 'id');
            return is_int($userUid) && intval($userUid) != PHP_INT_MAX;
        }
        return false;
    }

    public function getAllGroups(FrontendUser $frontendUser): array
    {
        $return = [];
        $loopProtection = [];
        foreach ($frontendUser->getUsergroup() as $frontendUserGroup) {
            $return = array_merge($return, $this->getSubGroups($frontendUserGroup, $loopProtection));
        }
        return $return;
    }

    /**
     *
     * @param FrontendUserGroup $frontendUserGroup
     * @param array $loopProtection
     * @return array
     */
    private function getSubGroups(FrontendUserGroup $frontendUserGroup, array &$loopProtection)
    {
        if (in_array($frontendUserGroup->getUid(), $loopProtection)) {
            return [];
        }
        $loopProtection[] = $frontendUserGroup->getUid();
        $return = [
            $frontendUserGroup
        ];
        /**
         *
         * @var FrontendUserGroup $subgroup
         */
        foreach ($frontendUserGroup->getSubgroup() as $subgroup) {
            $return = array_merge($return, $this->getSubGroups($subgroup, $loopProtection));
        }
        return $return;
    }
}
