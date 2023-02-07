<?php
declare(strict_types = 1);

use Cylancer\CySendMails\Domain\Model\FrontendUserGroup;
use Cylancer\CySendMails\Domain\Model\FrontendUser;
 
return [
    FrontendUser::class => [
        'tableName' => 'fe_users',
    ],
    FrontendUserGroup::class => [
        'tableName' => 'fe_groups',
    ],
    
];  
