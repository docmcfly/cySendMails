<?php
namespace Cylancer\CySendMails\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023 C. Gogolin <service@cylancer.net>
 *
 * @package Cylancer\CySendMails\Domain\Repository
 *         
 */
class MessageRepository extends Repository
{

    /**
     *
     * @param array $storagePageIds
     */
    public function setStorageUids(array $storagePageIds):void{
        /** @var QuerySettingsInterface $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setStoragePageIds($storagePageIds);
        $this->setDefaultQuerySettings($querySettings);
    }
    
}