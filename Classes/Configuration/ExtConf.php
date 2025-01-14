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

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\MathUtility;

#[Autoconfigure(constructor: 'create')]
final readonly class ExtConf
{
    private const EXT_KEY = 'plesk_widget';

    private const DEFAULT_SETTINGS = [
        'host' => '',
        'port' => 8443,
        'username' => '',
        'password' => '',
        'diskUsageType' => '%',
        'domain' => '',
    ];

    public function __construct(
        private string $host,
        private int $port,
        private string $username,
        private string $password,
        private DiskUsageTypeEnum $diskUsageType,
        private string $domain,
    ) {}

    public static function create(ExtensionConfiguration $extensionConfiguration): self
    {
        $extensionSettings = self::DEFAULT_SETTINGS;

        // Overwrite default extension settings with values from EXT_CONF
        try {
            $extensionSettings = array_merge($extensionSettings, $extensionConfiguration->get(self::EXT_KEY));
        } catch (ExtensionConfigurationExtensionNotConfiguredException|ExtensionConfigurationPathDoesNotExistException) {
        }

        // Make sure port is integer. Else: Use default value
        if (!MathUtility::canBeInterpretedAsInteger($extensionSettings['port'])) {
            $extensionSettings['port'] = self::DEFAULT_SETTINGS['port'];
        }

        // Convert value to DiskUsageType enum. If not found use default value to convert to DiskUsageType
        if (($extensionSettings['diskUsageType'] = DiskUsageTypeEnum::tryFrom($extensionSettings['diskUsageType'])) === null) {
            $extensionSettings['diskUsageType'] = DiskUsageTypeEnum::from(self::DEFAULT_SETTINGS['diskUsageType']);
        }

        return new self(
            host: (string)$extensionSettings['host'],
            port: (int)$extensionSettings['port'],
            username: (string)$extensionSettings['username'],
            password: (string)$extensionSettings['password'],
            diskUsageType: $extensionSettings['diskUsageType'],
            domain: (string)$extensionSettings['domain'],
        );
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

    public function getDiskUsageType(): DiskUsageTypeEnum
    {
        return $this->diskUsageType;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }
}
