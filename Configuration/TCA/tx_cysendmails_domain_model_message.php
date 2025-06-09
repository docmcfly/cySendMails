<?php

/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 C. Gogolin <service@cylancer.net>
 *
 *         
 */

return [
    'ctrl' => [
        'title' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cysendmails_domain_model_message.title',
        'label' => 'subject',
        'sortby' => 'tstamp',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'type' => 'type',
            'subject' => 'subject',
            'message' => 'message',
        ],
        'searchFields' => 'crdate, subject, message, attachments_meta_data',
        'iconfile' => 'EXT:cy_send_mails/Resources/Public/Icons/tx_cysendmails_domain_model_message.gif'
    ],
    'types' => [
        '1' => [
            'showitem' => 'crdate, sender, receivers, subject, message, attachments_meta_data'
        ]
    ],
    'columns' => [
        'crdate' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.creationDate',
            'config' => [
                'type' => 'datetime',
                'firmat' => 'datetime',
                'default' => time(),
                'readOnly' => true,
            ]
        ],
        'subject' => [
            'label' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cysendmails_domain_model_message.subject',
            'config' => [
                'type' => 'input',
                'max' => 255,
                'readOnly' => true,
            ],
        ],
        'sender' => [
            'label' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cysendmails_domain_model_message.sender',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'readOnly' => true,
            ]
        ],
        'receivers' => [
            'label' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cysendmails_domain_model_message.receivers',
            'config' => [
                'type' => 'text',
                'max' => 65535,
                'cols' => 60,
                'rows' => 3,
                'readOnly' => true
            ]
        ],
        'message' => [
            'label' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cysendmails_domain_model_message.message',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'max' => 1024 * 1024,
                'cols' => 60,
                'rows' => 25,
                'readOnly' => true
            ]
        ],
        'attachments_meta_data' => [
            'label' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cysendmails_domain_model_message.attachmentsMetaData',
            'config' => [
                'type' => 'text',
                'max' => 1024 * 1024,
                'cols' => 60,
                'rows' => 25,
                'readOnly' => true
            ]
        ],
    ]
];