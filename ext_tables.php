<?php
defined('TYPO3') || die('Access denied.');

// automatic garbage collention of stored messages
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['TYPO3\CMS\Scheduler\Task\TableGarbageCollectionTask']['options']['tables']['tx_cysendmails_domain_model_message'] = [
    'dateField' => 'tstamp',
    'expirePeriod' => 28
];