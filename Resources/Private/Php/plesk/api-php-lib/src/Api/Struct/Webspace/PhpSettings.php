<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Webspace;

class PhpSettings extends \PleskX\Api\Struct
{
    /** @var array */
    public $properties;

    public function __construct($apiResponse)
    {
        $this->properties = [];

        foreach ($apiResponse->webspace->get->result->data->{'php-settings'}->setting as $setting) {
            $this->properties[(string)$setting->name] = (string)$setting->value;
        }
    }
}
