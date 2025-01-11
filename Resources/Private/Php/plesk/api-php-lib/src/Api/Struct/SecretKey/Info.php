<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\SecretKey;

class Info extends \PleskX\Api\Struct
{
    /** @var string */
    public $key;

    /** @var string */
    public $ipAddress;

    /** @var string */
    public $description;

    /** @var string */
    public $login;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'key',
            'ip_address',
            'description',
            'login',
        ]);
    }
}
