<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Plesk;

use PleskX\Api\Client;
use PleskX\Api\Struct\Site\GeneralInfo;

class Site
{
    private Client $pleskClient;

    private GeneralInfo $generalInformation;

    public function __construct(Client $pleskClient, GeneralInfo $generalInfo)
    {
        $this->pleskClient = $pleskClient;
        $this->generalInformation = $generalInfo;
    }

    public function getGeneralInformation(): GeneralInfo
    {
        return $this->generalInformation;
    }

    public function getHosting(): ?Hosting
    {
        $response = $this->pleskClient->request([
            'site' => [
                'get' => [
                    'filter' => [
                        'guid' => $this->generalInformation->guid,
                    ],
                    'dataset' => [
                        'hosting' => null,
                    ]
                ],
            ],
        ]);

        if (!property_exists($response->data->hosting, 'vrt_hst')) {
            return null;
        }

        return new Hosting(
            $response->data->hosting->vrt_hst->ip_address,
            $response->data->hosting->vrt_hst->property
        );
    }
}
