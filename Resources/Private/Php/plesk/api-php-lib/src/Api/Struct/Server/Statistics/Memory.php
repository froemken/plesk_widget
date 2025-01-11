<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Server\Statistics;

class Memory extends \PleskX\Api\Struct
{
    /** @var int */
    public $total;

    /** @var int */
    public $used;

    /** @var int */
    public $free;

    /** @var int */
    public $shared;

    /** @var int */
    public $buffer;

    /** @var int */
    public $cached;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'total',
            'used',
            'free',
            'shared',
            'buffer',
            'cached',
        ]);
    }
}
