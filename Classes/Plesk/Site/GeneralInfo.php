<?php

declare(strict_types = 1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Plesk\Site;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $asciiName;

    /** @var string */
    public $crDate;

    /** @var string */
    public $description;

    /** @var array */
    public $dnsIpAddress;

    /** @var string */
    public $guid;

    /** @var string */
    public $htype;

    /** @var string */
    public $name;

    /** @var string */
    public $realSize;

    /** @var string */
    public $status;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'ascii-name',
            'cr_date',
            'description',
            'guid',
            'htype',
            'name',
            'real_size',
            'status',
        ]);

        foreach ($apiResponse->dns_ip_address as $address) {
            $this->dnsIpAddress[] = $address;
        }
    }
}
