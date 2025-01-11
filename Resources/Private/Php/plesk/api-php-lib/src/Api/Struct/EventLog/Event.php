<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\EventLog;

class Event extends \PleskX\Api\Struct
{
    /** @var string */
    public $type;

    /** @var int */
    public $time;

    /** @var string */
    public $class;

    /** @var string */
    public $id;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'type',
            'time',
            'class',
            'id',
        ]);
    }
}
