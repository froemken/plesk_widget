<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Plesk;

use PleskX\Api\XmlResponse;

class Hosting
{
    private array $ipAddresses;

    private array $properties;

    /**
     * @param XmlResponse[] $xmlIpAddresses
     * @param XmlResponse[] $xmlProperties
     */
    public function __construct(iterable $xmlIpAddresses, iterable $xmlProperties)
    {
        foreach ($xmlIpAddresses as $xmlIpAddress) {
            $this->ipAddresses[] = (string)$xmlIpAddress;
        }
        foreach ($xmlProperties as $xmlProperty) {
            $this->properties[(string)$xmlProperty->name] = (string)$xmlProperty->value;
        }
    }

    public function getIpAddresses(): array
    {
        return $this->ipAddresses;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }
}
