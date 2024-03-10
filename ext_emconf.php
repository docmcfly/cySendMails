<?php

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
$EM_CONF[$_EXTKEY] = [
    'title' => 'Send a mail to other frontend user or frontend user groups.',
    'description' => 'This extension allows frontend users to write emails to other 
frontend users without them knowing their email address. This extension is specifically 
for a manageable group of people. For example a club. The members can easily send emails 
to each other. ',
    'category' => 'plugin',
    'author' => 'C. Gogolin',
    'author_email' => 'service@cylancer.net',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
            'bootstrap_package' => '11.0.2-13.0.99'
        ],
        'conflicts' => [],
        'suggests' => []
    ]
];

/** ---- CHANGELOG ----------
 
 2.0.0 :: UPD: to TYPO3 12.4.x
 1.2.1 :: MTN: Extract the session form key handling in a service. 
 1.2.0 :: Fix: Fix the plugin configuration / registry
 1.1.0 :: ADD: Add a simulation mode and a back button.
 1.0.4 :: Fix: Better handling of unknown receiver ids.
 1.0.3 :: Fix: translation texts in the email templates
 1.0.2 :: Update: Bootstrap dependencies to version 13.0.* / add own jQuery lib
 1.0.1 :: Add a migration wizzard
 1.0.0 :: Initial
 
 // ---- CHANGELOG ---------- */

