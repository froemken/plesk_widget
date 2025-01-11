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
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.24',
            'dashboard' => '12.4.24',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
