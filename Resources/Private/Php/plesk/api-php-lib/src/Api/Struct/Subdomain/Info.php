<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Subdomain;

class Info extends \PleskX\Api\Struct
{
    /** @var int */
    public $id;

    /** @var string */
    public $parent;

    /** @var string */
    public $name;

    /** @var array */
    public $properties;

    public function __construct($apiResponse)
    {
        $this->properties = [];
        $this->_initScalarProperties($apiResponse, [
            'id',
            'parent',
            'name',
        ]);
        foreach ($apiResponse->property as $propertyInfo) {
            $this->properties[(string)$propertyInfo->name] = (string)$propertyInfo->value;
        }
    }
}
