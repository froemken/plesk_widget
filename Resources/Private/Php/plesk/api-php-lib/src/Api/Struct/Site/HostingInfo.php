<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Site;

class HostingInfo extends \PleskX\Api\Struct
{
    /** @var array */
    public $properties = [];

    /** @var string */
    public $ipAddress;

    public function __construct($apiResponse)
    {
        foreach ($apiResponse->vrt_hst->property as $property) {
            $this->properties[(string)$property->name] = (string)$property->value;
        }
        $this->_initScalarProperties($apiResponse->vrt_hst, [
            'ip_address',
        ]);
    }
}
