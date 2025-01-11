<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\EventLog;

class DetailedEvent extends \PleskX\Api\Struct
{
    /** @var int */
    public $id;

    /** @var string */
    public $type;

    /** @var int */
    public $time;

    /** @var string */
    public $class;

    /** @var string */
    public $objectId;

    /** @var string */
    public $user;

    /** @var string */
    public $host;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'type',
            'time',
            'class',
            ['obj_id' => 'objectId'],
            'user',
            'host',
        ]);
    }
}
