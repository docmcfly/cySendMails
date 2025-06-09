<?php
namespace Cylancer\CySendMails\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
 
/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 C. Gogolin <service@cylancer.net>
 *
 */         
class FrontendUserRepository extends Repository
{

    // protected $defaultOrderings = ['sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING];
    /**
     *
     * @var array
     * @param string $table
     *            table name
     * @return QueryBuilder
     */
    protected function getQueryBuilder(string $table)
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
    }

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
    
    /**
     *
     * @param array<Integer> $frontendUserGroupUid
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
     */
    public function findByUserGroups(array $frontendUserGroupUid)
    {
        if (empty($frontendUserGroupUid)) {
            return [];
        }

        $qb = $this->getQueryBuilder('fe_users');
        $qb->select('uid')->from('fe_users');

        /** @var int $uid */
        foreach ($frontendUserGroupUid as $uid) {
            $qb->orWhere($qb->expr()
                ->inSet('usergroup', $uid));
        }

        $s = $qb->executeQuery();
        $return = array();
        while ($row = $s->fetchAssociative()) {
            $return[] = $row['uid'];
        }
        return $return;
    }
}