<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Builder;

/*
 * Builder to create ExtConf object
 */

use StefanFroemken\PleskWidget\Configuration\CredentialsConfiguration;
use StefanFroemken\PleskWidget\Configuration\DiskUsageTypeEnum;
use StefanFroemken\PleskWidget\Configuration\ExtConf;
use StefanFroemken\PleskWidget\Configuration\ViewConfiguration;
use TYPO3\CMS\Core\Utility\MathUtility;
use ValueError;

class ExtConfBuilder
{
    private string $host = '';

    private int $port = 8443;

    private string $username = '';

    private string $password = '';

    private DiskUsageTypeEnum $diskUsageType = DiskUsageTypeEnum::PERCENT;

    private string $domain = '';

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function setPort(string $port): self
    {
        if (MathUtility::canBeInterpretedAsInteger($port)) {
            $this->port = (int)$port;
        }

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setDiskUsageType(string $diskUsageType): self
    {
        try {
            $this->diskUsageType = DiskUsageTypeEnum::from($diskUsageType);
        } catch (ValueError) {
            // Do nothing, keep default value
        }

        return $this;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function buildExtConf(): ExtConf
    {
        return new ExtConf(
            $this->buildCredentialsConfiguration(),
            $this->buildViewConfiguration(),
        );
    }

    private function buildCredentialsConfiguration(): CredentialsConfiguration
    {
        return new CredentialsConfiguration(
            $this->host,
            $this->port,
            $this->username,
            $this->password,
        );
    }

    private function buildViewConfiguration(): ViewConfiguration
    {
        return new ViewConfiguration($this->diskUsageType, $this->domain);
    }
}
