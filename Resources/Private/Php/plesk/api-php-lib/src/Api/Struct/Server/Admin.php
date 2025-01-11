<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Server;

class Admin extends \PleskX\Api\Struct
{
    /** @var string */
    public $companyName;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['admin_cname' => 'companyName'],
            ['admin_pname' => 'name'],
            ['admin_email' => 'email'],
        ]);
    }
}
