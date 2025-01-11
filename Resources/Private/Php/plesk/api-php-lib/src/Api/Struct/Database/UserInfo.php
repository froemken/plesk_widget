<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Database;

class UserInfo extends \PleskX\Api\Struct
{
    /** @var int */
    public $id;

    /** @var string */
    public $login;

    /** @var int */
    public $dbId;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'login',
            'db-id',
        ]);
    }
}
