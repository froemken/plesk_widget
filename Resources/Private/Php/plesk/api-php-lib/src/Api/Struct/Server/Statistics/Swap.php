<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Server\Statistics;

class Swap extends \PleskX\Api\Struct
{
    /** @var int */
    public $total;

    /** @var int */
    public $used;

    /** @var int */
    public $free;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'total',
            'used',
            'free',
        ]);
    }
}
