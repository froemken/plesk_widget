<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Plesk Widget',
    'description' => 'Shows information about your plesk based customer control center',
    'category' => 'plugin',
    'author' => 'Stefan Froemken',
    'author_email' => 'froemken@gmail.com',
    'author_company' => '',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        /*'classmap' => [
            'Resources/Private/Php/'
        ],*/
        'psr-4' => [
            //'StefanFroemken\\PleskWidget\\' => 'Classes',
            'PleskX\\' => 'Resources/Private/Php/plesk/api-php-lib/src',
        ],
    ],
];
