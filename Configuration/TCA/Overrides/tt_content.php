<?php


$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['cysendmails_messageform'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    // plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
    'cysendmails_messageform',
    // Flexform configuration schema file
    'FILE:EXT:cy_send_mails/Configuration/FlexForms/MessageForm.xml'
    );
