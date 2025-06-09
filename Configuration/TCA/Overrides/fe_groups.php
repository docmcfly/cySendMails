<?php

defined('TYPO3') || die('Access denied.');

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;


$translationPath = 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:fe_groups';

ExtensionManagementUtility::addTCAcolumns(
    'fe_groups',
    [
        'receiver_group' => [
            'exclude' => 0,
            'label' => "$translationPath.receiverGroups",
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'fe_groups',
                'foreign_table_where' => " AND {#fe_groups}.{#receiver_group_name} <> '' ",
                'MM' => 'tx_cysendmails_fegroups_receivergroup_mm',
            ],

        ],
        'receiver_group_name' => [
            'exclude' => 0,
            'label' => "$translationPath.receiverGroupName",
            'config' => [
                'type' => 'input',
                'eval' => 'trim, unique',
                'size' => 40,
            ],

        ]
    ]
);

ExtensionManagementUtility::addToAllTCAtypes(
    'fe_groups',
    "--div--;$translationPath.tabSettings, receiver_group, receiver_group_name"
);
