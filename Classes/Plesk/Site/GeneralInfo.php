<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Plesk\Site;

class GeneralInfo extends \PleskX\Api\Struct
{
    public string $asciiName = '';

    public string $crDate = '';

    public string $description = '';

    public array $dnsIpAddress = [];

    public string $guid;

    public string $htype;

    public string $name;

    public string $realSize;

    public string $status;

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
