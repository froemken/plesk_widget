<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\ProtectedDirectory;

use PleskX\Api\Struct;

class DataInfo extends Struct
{
    /** @var string */
    public $name;

    /** @var string */
    public $header;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'name',
            'header',
        ]);
    }
}
