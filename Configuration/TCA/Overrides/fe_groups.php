<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied.');

if (!isset($GLOBALS['TCA']['fe_groups']['ctrl']['type'])) {
    // no type field defined, so we define it here. This will only happen the first time the extension is installed!!
    $GLOBALS['TCA']['fe_groups']['ctrl']['type'] = 'tx_extbase_type';
    $tempColumnstx_participants_fe_groups = [];
    $tempColumnstx_participants_fe_groups[$GLOBALS['TCA']['fe_groups']['ctrl']['type']] = [
        'exclude' => true,
        'label'   => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cy_send_mails.tx_extbase_type',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['',''],
                ['Tx_CySendMails_FrontendUserGroup','Tx_CySendMails_FrontendUserGroup']
            ],
            'default' => 'Tx_Extbase_Domain_Model_FrontendUserGroup',
            'size' => 1,
            'maxitems' => 1,
        ]
    ];
    ExtensionManagementUtility::addTCAcolumns('fe_groups', $tempColumnstx_participants_fe_groups);
}

ExtensionManagementUtility::addToAllTCAtypes(
    'fe_groups',
    $GLOBALS['TCA']['fe_groups']['ctrl']['type'],
    '',
    'after:' . $GLOBALS['TCA']['fe_groups']['ctrl']['label']
);

$tmp_sendmessage_columns = [ 
    'receiver_group' => [
        'label' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:fe_groups.receiverGroups',
        'config' => [
            'type' => 'select',
             'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'fe_groups',
            'foreign_table_where' => " AND {#fe_groups}.{#receiver_group_name} <> '' ",
            'MM' => 'tx_cysendmails_fegroups_receivergroup_mm',
        ],
        
    ],
    'receiver_group_name' => [
        'label' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:fe_groups.receiverGroupName',
        'config' => [
            'type' => 'input',
            'eval' => 'trim, unique',
            'size' => 40,
        ],
        
    ],
];

ExtensionManagementUtility::addTCAcolumns('fe_groups',$tmp_sendmessage_columns);

/* inherit and extend the show items from the parent class */

if (isset($GLOBALS['TCA']['fe_groups']['types']['0']['showitem'])) {
    $GLOBALS['TCA']['fe_groups']['types']['Tx_CySendMails_FrontendUserGroup']['showitem'] = $GLOBALS['TCA']['fe_groups']['types']['0']['showitem'];
} elseif(is_array($GLOBALS['TCA']['fe_groups']['types'])) {
    // use first entry in types array
    $fe_groups_type_definition = reset($GLOBALS['TCA']['fe_groups']['types']);
    $GLOBALS['TCA']['fe_groups']['types']['Tx_CySendMails_FrontendUserGroup']['showitem'] = $fe_groups_type_definition['showitem'];
} else {
    $GLOBALS['TCA']['fe_groups']['types']['Tx_CySendMails_FrontendUserGroup']['showitem'] = '';
}

$GLOBALS['TCA']['fe_groups']['columns'][$GLOBALS['TCA']['fe_groups']['ctrl']['type']]['config']['items'][] = ['LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cy_send_mails.tx_extbase_type', 'Tx_CySendMails_FrontendUserGroup'];
$GLOBALS['TCA']['fe_groups']['types']['Tx_Extbase_Domain_Model_FrontendUserGroup']['showitem'] .= ', receiver_group,receiver_group_name';
$GLOBALS['TCA']['fe_groups']['types']['Tx_CySendMails_FrontendUserGroup']['showitem'] .= ', receiver_group,receiver_group_name';
$GLOBALS['TCA']['fe_groups']['types']['0']['showitem'] .= ', receiver_group, receiver_group_name';



