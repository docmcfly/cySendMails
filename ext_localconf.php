<?php
use Cylancer\CySendMails\Controller\MessageFormController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 C. Gogolin <service@cylancer.net>
 *
 */

defined('TYPO3') || die('Access denied.');

ExtensionUtility::configurePlugin(
    'CySendMails',
    'MessageForm',
    [
        MessageFormController::class => 'show, send'
    ],
    // non-cacheable actions
    [
        MessageFormController::class => 'show, send'
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

$GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths']['cy_send_mails'] = 'EXT:cy_send_mails/Resources/Private/Templates/MessageMail/';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths']['cy_send_mails'] = 'EXT:cy_send_mails/Resources/Private/Layouts/MessageMail/';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['partialRootPaths']['cy_send_mails'] = 'EXT:cy_send_mails/Resources/Private/Partials/MessageMail/';
