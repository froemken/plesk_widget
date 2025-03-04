<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Customer;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $company;

    /** @var string */
    public $personalName;

    /** @var string */
    public $login;

    /** @var string */
    public $guid;

    /** @var string */
    public $email;

    /** @var string */
    public $phone;

    /** @var string */
    public $fax;

    /** @var string */
    public $address;

    /** @var string */
    public $postalCode;

    /** @var string */
    public $city;

    /** @var string */
    public $state;

    /** @var string */
    public $country;

    /** @var string */
    public $description;

    /** @var string */
    public $externalId;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['cname' => 'company'],
            ['pname' => 'personalName'],
            'login',
            'guid',
            'email',
            'phone',
            'fax',
            'address',
            ['pcode' => 'postalCode'],
            'city',
            'state',
            'country',
            'external-id',
            'description',
        ]);
    }
}
