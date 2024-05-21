<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cysendmails_domain_model_message.title',
        'label' => 'subject',
        'sortby' => 'tstamp',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
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
    'interface' => [
        'showRecordFieldList' => 'crdate, subject, message, attachments_meta_data' 
    ],
    'types' => [
        '1' => [
            'showitem' => 'sender, receivers, subject, message, attachments_meta_data'
        ]
    ],
    'columns' => [
        'subject' => [
            'label' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cysendmails_domain_model_message.subject',
            'config' => [
                'type' => 'input',
                'max' => 255,
            ],
        ],
        
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        - 1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255
            ]
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ]
            ]
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ]
        ],
        'crdate' => [
            'label' => 'crdate',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'default' => time(),
            ]
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
            ]
        ],
        'sender' => [
            'label' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cysendmails_domain_model_message.sender',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'readonly' => false,
            ]
        ],
        'receivers' => [
            'label' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cysendmails_domain_model_message.receivers',
            'config' => [
                'type' => 'text',
                'max' => 65535,
                'cols' => 60,
                'rows' => 3,
                'readonly' => false
            ]
        ],
        'message' => [
            'label' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cysendmails_domain_model_message.message',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'max' => 1024*1024,
                'cols' => 60,
                'rows' => 25,
                'readonly' => false
            ]
        ],
        'attachments_meta_data' => [
            'label' => 'LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_db.xlf:tx_cysendmails_domain_model_message.attachmentsMetaData',
            'config' => [
                'type' => 'text',
                'max' => 1024*1024,
                'cols' => 60,
                'rows' => 25,
                'readonly' => false
            ]
        ],
    ]
];