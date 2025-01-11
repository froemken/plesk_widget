<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Server;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $serverName;

    /** @var string */
    public $serverGuid;

    /** @var string */
    public $mode;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'server_name',
            'server_guid',
            'mode',
        ]);
    }
}
