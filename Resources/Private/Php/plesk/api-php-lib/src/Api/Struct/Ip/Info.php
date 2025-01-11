<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Ip;

class Info extends \PleskX\Api\Struct
{
    /** @var string */
    public $ipAddress;

    /** @var string */
    public $netmask;

    /** @var string */
    public $type;

    /** @var string */
    public $interface;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'ip_address',
            'netmask',
            'type',
            'interface',
        ]);
    }
}
