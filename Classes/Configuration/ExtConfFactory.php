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
use TYPO3\CMS\Core\Utility\MathUtility;
use ValueError;

readonly class ExtConfFactory
{
    private const DEFAULT_SETTINGS = [
        'host' => '',
        'port' => 8443,
        'username' => '',
        'password' => '',
        'diskUsageType' => '%',
        'domain' => '',
    ];

    public function __construct(private ExtensionConfiguration $extensionConfiguration) {}

    public function create(): ExtConf
    {
        return new ExtConf($this->getExtensionSettings());
    }

    private function getExtensionSettings(): array
    {
        $extensionSettings = self::DEFAULT_SETTINGS;

        try {
            $extensionSettings = (array)$this->extensionConfiguration->get('plesk_widget');

            // Remove whitespaces
            $extensionSettings = array_map('trim', $extensionSettings);

            // remove empty values
            $extensionSettings = array_filter($extensionSettings);

            $extensionSettings = array_merge(self::DEFAULT_SETTINGS, $extensionSettings);

            // Special handling for integer value "port"
            if (MathUtility::canBeInterpretedAsInteger($extensionSettings['port'])) {
                $extensionSettings['port'] = (int)$extensionSettings['port'];
            } else {
                $extensionSettings['port'] = self::DEFAULT_SETTINGS['port'];
            }

            // Migrate diskUsageType to ENUM
            try {
                $extensionSettings['diskUsageType'] = DiskUsageTypeEnum::from(
                    (string)$extensionSettings['diskUsageType']
                );
            } catch (ValueError) {
                $extensionSettings['diskUsageType'] = DiskUsageTypeEnum::from(self::DEFAULT_SETTINGS['diskUsageType']);
            }
        } catch (ExtensionConfigurationExtensionNotConfiguredException) {
            // Do nothing. Keep the default values
        } catch (ExtensionConfigurationPathDoesNotExistException) {
            // Will never be thrown, as $path is not given
        }

        return $extensionSettings;
    }
}
