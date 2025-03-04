<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\PhpHandler;

use PleskX\Api\Struct;

class Info extends Struct
{
    /** @var string */
    public $id;

    /** @var string */
    public $displayName;

    /** @var string */
    public $fullVersion;

    /** @var string */
    public $version;

    /** @var string */
    public $type;

    /** @var string */
    public $path;

    /** @var string */
    public $clipath;

    /** @var string */
    public $phpini;

    /** @var string */
    public $custom;

    /** @var string */
    public $handlerStatus;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'display-name',
            'full-version',
            'version',
            'type',
            'path',
            'clipath',
            'phpini',
            'custom',
            'handler-status',
        ]);
    }
}
