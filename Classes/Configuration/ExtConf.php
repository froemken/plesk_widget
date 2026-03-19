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
        'diskUsageType' => '%',
    ];

    public function __construct(
        private DiskUsageTypeEnum $diskUsageType,
    ) {}

    public static function create(ExtensionConfiguration $extensionConfiguration): self
    {
        $extensionSettings = self::DEFAULT_SETTINGS;

        // Overwrite default extension settings with values from EXT_CONF
        try {
            $extensionSettings = array_merge(
                $extensionSettings,
                $extensionConfiguration->get(self::EXT_KEY)
            );
        } catch (ExtensionConfigurationExtensionNotConfiguredException|ExtensionConfigurationPathDoesNotExistException) {
        }

        // Try to convert diskUsageType to DiskUsageTypeEnum.
        // If "try" fails to null, convert default value to DiskUsageType
        if (($extensionSettings['diskUsageType'] = DiskUsageTypeEnum::tryFrom($extensionSettings['diskUsageType'])) === null) {
            $extensionSettings['diskUsageType'] = DiskUsageTypeEnum::from(self::DEFAULT_SETTINGS['diskUsageType']);
        }

        return new self(
            diskUsageType: $extensionSettings['diskUsageType'],
        );
    }

    public function getDiskUsageType(): DiskUsageTypeEnum
    {
        return $this->diskUsageType;
    }
}
