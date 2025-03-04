<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Reseller;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var int */
    public $id;

    /** @var string */
    public $personalName;

    /** @var string */
    public $login;

    /** @var array */
    public $permissions;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse->{'gen-info'}, [
            ['pname' => 'personalName'],
            'login',
        ]);

        $this->permissions = [];
        foreach ($apiResponse->permissions->permission as $permissionInfo) {
            $this->permissions[(string)$permissionInfo->name] = (string)$permissionInfo->value;
        }
    }
}
