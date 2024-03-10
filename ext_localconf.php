<?php
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use Cylancer\CySendMails\Controller\MessageFormController;
use Cylancer\CySendMails\Upgrades\SendMessagesMigrationWizard;

/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2024 C. Gogolin <service@cylancer.net>
 *
 */

 defined('TYPO3') || die('Access denied.');

call_user_func(function () {

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'CySendMails',
        'MessageForm',
        [
            MessageFormController::class => 'show, send'
        ],
        // non-cacheable actions
        [
            MessageFormController::class => 'show, send'
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    messageform {
                        iconIdentifier = cysendmails-plugin-messageform
                        title = LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_be_messageForm.xlf:plugin.name
                        description = LLL:EXT:cy_send_mails/Resources/Private/Language/locallang_be_messageForm.xlf:plugin.description
                        tt_content_defValues {
                            CType = list
                            list_type = cysendmails_messageform
                        }
                    }
                }
                show = *
            }
       }');

    $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerIcon('cysendmails-plugin-messageform', SvgIconProvider::class, [
        'source' => 'EXT:cy_send_mails/Resources/Public/Icons/plugin_messageForm.svg'
    ]);


});

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['cynewsletter_sendMessagesMigrationWizard'] = SendMessagesMigrationWizard::class;


$GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths']['cy_send_mails']    = 'EXT:cy_send_mails/Resources/Private/Templates/MessageMail/';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths']['cy_send_mails']    = 'EXT:cy_send_mails/Resources/Private/Layouts/MessageMail/';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['partialRootPaths']['cy_send_mails']    = 'EXT:cy_send_mails/Resources/Private/Partials/MessageMail/';
