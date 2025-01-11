<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Webspace;

class PhysicalHostingDescriptor extends \PleskX\Api\Struct
{
    /** @var array */
    public $properties;

    public function __construct($apiResponse)
    {
        $this->properties = [];

        foreach ($apiResponse->descriptor->property as $propertyInfo) {
            $this->properties[(string)$propertyInfo->name] = new HostingPropertyInfo($propertyInfo);
        }
    }
}
