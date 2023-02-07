<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('Cylancer.CySendMails', 'MessageForm', 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_be_messageForm.xlf:plugin.name');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('cy_send_mails', 'Configuration/TypoScript', 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_be_messageForm.xlf:plugin.name');

    /**
     * Garbage Collector
     */
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['TYPO3\CMS\Scheduler\Task\TableGarbageCollectionTask']['options']['tables']['tx_cysendmails_domain_model_message'] = [
        'dateField' => 'tstamp',
        'expirePeriod' => 28
    ];
});
