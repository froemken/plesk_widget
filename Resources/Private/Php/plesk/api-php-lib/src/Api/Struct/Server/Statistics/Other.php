<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Server\Statistics;

class Other extends \PleskX\Api\Struct
{
    /** @var string */
    public $cpu;

    /** @var int */
    public $uptime;

    /** @var bool */
    public $insideVz;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'cpu',
            'uptime',
            ['inside_vz' => 'insideVz'],
        ]);
    }
}
