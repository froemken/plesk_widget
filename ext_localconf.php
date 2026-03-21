<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die();

use StefanFroemken\PleskWidget\Hook\DataHandlerHook;
use StefanFroemken\PleskWidget\PasswordPolicy\Validator\PleskPasswordValidator;

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['pleskWidgetEncryptPassword'] = DataHandlerHook::class;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['passwordPolicies']['plesk-widget'] = [
    'validators' => [
        PleskPasswordValidator::class => [
            'options' => [],
        ],
    ],
];
