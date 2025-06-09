<?php

use TYPO3\CMS\Core\Schema\Struct\SelectItem;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

$extension = 'cySendMails';
$plugin = 'messageForm';

$signatur = strtolower($extension . '_' . $plugin);
$iconIdentifier = $extension . '-' . $plugin;

$translationPath = 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_be_' . $plugin . '.xlf:';

ExtensionManagementUtility::addPlugin(
    new SelectItem(
        'select',
        $translationPath . 'plugin.name',
        $signatur,
        $iconIdentifier,
        'cySendMails',
        $translationPath . 'plugin.description',
    ),
    'CType',
    $extension
);

ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;' . $translationPath . 'flexforms_general.title,pi_flexform',
    $signatur,
    'after:palette:headers'
);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:cy_send_mails/Configuration/Flexforms/' . $plugin . '.xml',
    $signatur,
);
