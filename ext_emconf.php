<?php

/**
 *
 * This file is part of the "cy_send_mails" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2023 C. Gogolin <service@cylancer.net>
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
    'version' => '1.0.2',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.12-11.5.99',
            'bootstrap_package' => '11.0.2-12.9.99'
        ],
        'conflicts' => [],
        'suggests' => []
    ]
];

/** ---- CHANGELOG ----------
 1.0.0 :: Initial
 1.0.1 :: Add a migration wizzard
 
 
 // ---- CHANGELOG ---------- */

