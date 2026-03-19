<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

return [
    'ctrl' => [
        'title' => 'plesk_widget.db:pleskServer',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'default_sortby' => 'ORDER BY title',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'adminOnly' => true,
        'rootLevel' => 1,
        'groupName' => 'plesk_widget',
        'typeicon_classes' => [
            'default' => 'information-webserver',
        ],
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
    ],
    'types' => [
        0 => [
            'showitem' => '
                --palette--;plesk_widget.db:palette.title;title,
                --palette--;plesk_widget.db:palette.pleskServer;pleskServer,
                --palette--;plesk_widget.db:palette.pleskCredentials;pleskCredentials,
                --palette--;plesk_widget.db:palette.domain;domain,
                --div--;frontend.tca:pages.tabs.access,
                --palette--;frontend.tca:pages.palettes.access;access',
        ],
    ],
    'palettes' => [
        'title' => [
            'showitem' => 'title, hidden',
        ],
        'pleskServer' => [
            'showitem' => 'host, port',
        ],
        'pleskCredentials' => [
            'showitem' => 'username, password',
        ],
        'domain' => [
            'showitem' => 'domain',
        ],
        'access' => [
            'showitem' => 'starttime;frontend.ttc:starttime_formlabel,endtime;frontend.ttc:endtime_formlabel',
        ],
    ],
    'columns' => [
        'title' => [
            'exclude' => true,
            'label' => 'plesk_widget.db:title',
            'description' => 'plesk_widget.db:title.description',
            'config' => [
                'type' => 'input',
                'required' => true,
                'max' => 255,
                'eval' => 'trim',
                'placeholder' => 'My Plesk Server for example.com',
            ],
        ],
        'host' => [
            'exclude' => true,
            'label' => 'plesk_widget.db:host',
            'description' => 'plesk_widget.db:host.description',
            'config' => [
                'type' => 'input',
                'required' => true,
                'max' => 255,
                'placeholder' => 'plesk-server.com or IP address',
            ],
        ],
        'port' => [
            'exclude' => true,
            'label' => 'plesk_widget.db:port',
            'description' => 'plesk_widget.db:port.description',
            'config' => [
                'type' => 'number',
                'format' => 'integer',
                'max' => 5,
                'default' => 8443,
                'placeholder' => '8443',
            ],
        ],
        'username' => [
            'exclude' => true,
            'label' => 'plesk_widget.db:username',
            'description' => 'plesk_widget.db:username.description',
            'config' => [
                'type' => 'input',
                'required' => true,
                'max' => 255,
            ],
        ],
        'password' => [
            'exclude' => true,
            'label' => 'plesk_widget.db:password',
            'description' => 'plesk_widget.db:password.description',
            'config' => [
                'type' => 'input',
                'required' => true,
                'max' => 255,
            ],
        ],
        'domain' => [
            'exclude' => true,
            'label' => 'plesk_widget.db:domain',
            'description' => 'plesk_widget.db:domain.description',
            'config' => [
                'type' => 'input',
                'max' => 255,
                'placeholder' => 'example.com',
            ],
        ],
    ],
];
