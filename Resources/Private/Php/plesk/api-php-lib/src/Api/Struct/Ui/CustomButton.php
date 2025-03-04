<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Ui;

class CustomButton extends \PleskX\Api\Struct
{
    /** @var string */
    public $id;

    /** @var int */
    public $sortKey;

    /** @var bool */
    public $public;

    /** @var bool */
    public $internal;

    /** @var bool */
    public $noFrame;

    /** @var string */
    public $place;

    /** @var string */
    public $url;

    /** @var string */
    public $text;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, ['id']);
        $this->_initScalarProperties($apiResponse->properties, [
            'sort_key',
            'public',
            'internal',
            ['noframe' => 'noFrame'],
            'place',
            'url',
            'text',
        ]);
    }
}
