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
    'version' => '1.2.1',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.41-12.4.99',
            'dashboard' => '11.5.41-12.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
