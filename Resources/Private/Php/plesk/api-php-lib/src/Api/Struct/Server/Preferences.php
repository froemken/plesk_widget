<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Server;

class Preferences extends \PleskX\Api\Struct
{
    /** @var int */
    public $statTtl;

    /** @var int */
    public $trafficAccounting;

    /** @var int */
    public $restartApacheInterval;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'stat_ttl',
            'traffic_accounting',
            'restart_apache_interval',
        ]);
    }
}
