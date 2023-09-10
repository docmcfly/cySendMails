<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

(static function (): void{

    ExtensionUtility::registerPlugin(
        'CySendMails',
        'MessageForm',
        'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_be_messageForm.xlf:plugin.name'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['cysendmails_messageform'] = 'pi_flexform';
    ExtensionManagementUtility::addPiFlexFormValue(
        // plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
        'cysendmails_messageform',
        // Flexform configuration schema file
        'FILE:EXT:cy_send_mails/Configuration/FlexForms/MessageForm.xml'
    );
})();