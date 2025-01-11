<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Configuration;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\SingletonInterface;

/*
 * This class streamlines all settings from extension settings
 */
class ExtConf implements SingletonInterface
{
    private string $host;

    private int $port;

    private string $username;

    private string $password;

    private string $diskUsageType;

    private string $domain;

    public function __construct(ExtensionConfiguration $extensionConfiguration)
    {
        try {
            $extConf = (array)$extensionConfiguration->get('plesk_widget');

            $this->host = trim((string)($extConf['host'] ?? ''));
            $this->port = (int)($extConf['port'] ?? 8443);
            $this->username = trim((string)($extConf['username'] ?? ''));
            $this->password = trim((string)($extConf['password'] ?? ''));

            $this->diskUsageType = trim((string)($extConf['diskUsageType'] ?? '%'));
            $this->domain = trim((string)($extConf['domain'] ?? ''));
        } catch (ExtensionConfigurationExtensionNotConfiguredException $extensionConfigurationExtensionNotConfiguredException) {
            // Do nothing. The values will still be empty. We catch that as Exception just before the first API call
        } catch (ExtensionConfigurationPathDoesNotExistException $extensionConfigurationPathDoesNotExistException) {
            // Can never be called, as $path is not set
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
