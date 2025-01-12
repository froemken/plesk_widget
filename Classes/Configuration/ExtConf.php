<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Configuration;

/*
 * This class streamlines all settings from extension settings
 */
readonly class ExtConf
{
    private string $host;

    private int $port;

    private string $username;

    private string $password;

    private string $diskUsageType;

    private string $domain;

    public function __construct(array $extensionSettings)
    {
        foreach ($extensionSettings as $property => $value) {
            $this->{$property} = $value;
        }
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDiskUsageType(): string
    {
        return $this->diskUsageType;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }
}
