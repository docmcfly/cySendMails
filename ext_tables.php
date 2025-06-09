<?php
defined('TYPO3') || die('Access denied.');

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

// automatic garbage collention of stored messages
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['TYPO3\CMS\Scheduler\Task\TableGarbageCollectionTask']['options']['tables']['tx_cysendmails_domain_model_message'] = [
    'dateField' => 'tstamp',
    'expirePeriod' => 28
];