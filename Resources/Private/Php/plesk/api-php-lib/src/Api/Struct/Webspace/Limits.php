<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Webspace;

class Limits extends \PleskX\Api\Struct
{
    /** @var string */
    public $overuse;

    /** @var array */
    public $limits;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, ['overuse']);
        $this->limits = [];

        foreach ($apiResponse->limit as $limit) {
            $this->limits[(string)$limit->name] = new Limit($limit);
        }
    }
}
