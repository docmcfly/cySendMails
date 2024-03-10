<?php
declare(strict_types=1);
namespace Cylancer\CySendMails\Upgrades;

use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

final class SendMessagesMigrationWizard implements UpgradeWizardInterface
{

    /**
     * Return the speaking name of this wizard
     */
    public function getTitle(): string
    {
        return '[cylancer.net] send messages migration wizard';
    }

    /**
     * Return the description for this wizard
     */
    public function getDescription(): string
    {
        return '[cylancer.net] send messages migration wizard';
    }

    /**
     * Execute the update
     *
     * Called when a wizard reports that an update is necessary
     */
    public function executeUpdate(): bool
    {
        /** @var QueryBuilder $source */
        $source = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_sendmessage_fegroups_receivergroup_mm');
        $source->select('uid_local', 'uid_foreign', 'sorting', 'sorting_foreign')->from('tx_sendmessage_fegroups_receivergroup_mm');

        $sourceStatement = $source->execute();

        /** @var QueryBuilder $target */
        $target = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cysendmails_fegroups_receivergroup_mm');
        $target->insert('tx_cysendmails_fegroups_receivergroup_mm');

        while ($row = $sourceStatement->fetch()) {
            $target->values([
                'uid_local' => $row['uid_local'],
                'uid_foreign' => $row['uid_foreign'],
                'sorting' => $row['sorting'],
                'sorting_foreign' => $row['sorting_foreign']
            ])->executeStatement();
        }
        return true;
    }

    /**
     * Is an update necessary?
     *
     * Is used to determine whether a wizard needs to be run.
     * Check if data for migration exists.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {
        /** @var QueryBuilder $source */
        $source = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_sendmessage_fegroups_receivergroup_mm');
        $source->select('uid_local')
            ->from('tx_sendmessage_fegroups_receivergroup_mm')
            ->setMaxResults(1);
        try {
            $sourceStatement = $source->execute();
            if (!($row = $sourceStatement->fetch() !== false)) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
        /** @var QueryBuilder $target */
        $target = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cysendmails_fegroups_receivergroup_mm');
        $target->select('uid_local')
            ->from('tx_cysendmails_fegroups_receivergroup_mm')
            ->setMaxResults(1);

        $targetStatement = $target->execute();
        if ($row = $targetStatement->fetch() === false) {
            return true;
        }

        return false;
    }

    /**
     * Returns an array of class names of prerequisite classes
     *
     * This way a wizard can define dependencies like "database up-to-date" or
     * "reference index updated"
     *
     * @return string[]
     */
    public function getPrerequisites(): array
    {
        // Add your logic here
    }

    public function getIdentifier(): string
    {
        return 'cynewsletter_sendMessagesMigrationWizard';
    }
}