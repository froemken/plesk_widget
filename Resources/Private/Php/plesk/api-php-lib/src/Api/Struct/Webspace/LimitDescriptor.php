<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Webspace;

class LimitDescriptor extends \PleskX\Api\Struct
{
    /** @var array */
    public $limits;

    public function __construct($apiResponse)
    {
        $this->limits = [];

        foreach ($apiResponse->descriptor->property as $propertyInfo) {
            $this->limits[(string)$propertyInfo->name] = new LimitInfo($propertyInfo);
        }
    }
}
