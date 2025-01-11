<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Webspace;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $creationDate;

    /** @var string */
    public $name;

    /** @var string */
    public $asciiName;

    /** @var string */
    public $status;

    /** @var int */
    public $realSize;

    /** @var int */
    public $ownerId;

    /** @var array */
    public $ipAddresses = [];

    /** @var string */
    public $guid;

    /** @var string */
    public $vendorGuid;

    /** @var string */
    public $description;

    /** @var string */
    public $adminDescription;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['cr_date' => 'creationDate'],
            'name',
            'ascii-name',
            'status',
            'real_size',
            'owner-id',
            'guid',
            'vendor-guid',
            'description',
            'admin-description',
        ]);

        foreach ($apiResponse->dns_ip_address as $ip) {
            $this->ipAddresses[] = (string)$ip;
        }
    }
}
