<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Webspace;

class PermissionDescriptor extends \PleskX\Api\Struct
{
    /** @var array */
    public $permissions;

    public function __construct($apiResponse)
    {
        $this->permissions = [];

        foreach ($apiResponse->descriptor->property as $propertyInfo) {
            $this->permissions[(string)$propertyInfo->name] = new PermissionInfo($propertyInfo);
        }
    }
}
