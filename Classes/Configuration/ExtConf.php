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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/*
 * This class streamlines all settings from extension settings
 */
class ExtConf implements SingletonInterface
{
    protected string $diskUsageType = '%';

    protected string $host = '';

    protected int $port = 8443;

    protected string $username = '';

    protected string $password = '';

    public function __construct()
    {
        try {
            $extConf = (array)GeneralUtility::makeInstance(ExtensionConfiguration::class)
                ->get('plesk_widget');

            $this->diskUsageType = trim((string)($extConf['diskUsageType'] ?? ''));
            $this->host = trim((string)($extConf['host'] ?? ''));
            $this->port = (int)($extConf['port'] ?? 8443);
            $this->username = trim((string)($extConf['username'] ?? ''));
            $this->password = trim((string)($extConf['password'] ?? ''));
        } catch (ExtensionConfigurationExtensionNotConfiguredException $extensionConfigurationExtensionNotConfiguredException) {
            // Do nothing. The values will still be empty. We catch that as Exception just before the first API call
        } catch (ExtensionConfigurationPathDoesNotExistException $extensionConfigurationPathDoesNotExistException) {
            // Can never be called, as $path is not set
        }
    }

    public function getDiskUsageType(): string
    {
        return $this->diskUsageType;
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
        //return $this->username;
        return '';
    }

    public function getPassword(): string
    {
        //return $this->password;
        return '';
    }
}
