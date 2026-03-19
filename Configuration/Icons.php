<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'ext-plesk-widget-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:plesk_widget/Resources/Public/Icons/plesk.svg',
    ],
];
