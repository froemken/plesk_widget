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
                'type' => 'password',
                'hashed' => false,
                'required' => true,
                // This field stores encrypted data using TYPO3's CipherService (XChaCha20-Poly1305).
                // The 'plesk-widget' passwordPolicy (defined in ext_localconf.php) is applied via the
                // DataHandlerHook (processDatamap_preProcessFieldArray) and limits the plaintext input
                // to a maximum of 64 characters. Encryption happens in processDatamap_postProcessFieldArray.
                // The actual database column size must accommodate the encrypted output, which includes
                // the plaintext, a 24-byte nonce, a 16-byte authentication tag, and Base64 encoding overhead.
                // For a 64-character plaintext input, the encrypted string is approximately 140 characters long.
                // The database column (e.g., VARCHAR(255)) is sized to safely store this encrypted value.
                'passwordPolicy' => 'plesk-widget',
                'autocomplete' => false,
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
