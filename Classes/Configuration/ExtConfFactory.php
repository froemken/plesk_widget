<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Configuration;

use StefanFroemken\PleskWidget\Builder\ExtConfBuilder;
use StefanFroemken\PleskWidget\Builder\ExtConfBuilderFactory;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\MathUtility;
use ValueError;

readonly class ExtConfFactory
{
    /**
     * Stored values of ext_conf_template.txt are always string.
     * So, keep all values as string here, too.
     * They will be cast within their setter methods.
     */
    private const DEFAULT_SETTINGS = [
        'host' => '',
        'port' => '8443',
        'username' => '',
        'password' => '',
        'diskUsageType' => '%',
        'domain' => '',
    ];

    public function __construct(
        private ExtConfBuilderFactory $extConfBuilderFactory,
        private ExtensionConfiguration $extensionConfiguration
    ) {}

    public function createExtConf(): ExtConf
    {
        $extensionSettings = $this->getExtensionSettings();

        return $this->createExtConfBuilder()
            ->setHost($extensionSettings['host'])
            ->setPort($extensionSettings['port'])
            ->setUsername($extensionSettings['username'])
            ->setPassword($extensionSettings['password'])
            ->setDiskUsageType($extensionSettings['diskUsageType'])
            ->setDomain($extensionSettings['domain'])
            ->buildExtConf();
    }

    private function getExtensionSettings(): array
    {
        $extensionSettings = self::DEFAULT_SETTINGS;

        try {
            $extensionSettings = $this->sanitizeExtensionSettings(
                (array)$this->extensionConfiguration->get('plesk_widget')
            );

            return array_merge(self::DEFAULT_SETTINGS, $extensionSettings);
        } catch (ExtensionConfigurationExtensionNotConfiguredException) {
            // Do nothing. Keep the default values
        } catch (ExtensionConfigurationPathDoesNotExistException) {
            // Will never be thrown, as $path is not given
        }

        return $extensionSettings;
    }

    private function sanitizeExtensionSettings(array $extensionSettings): array
    {
        // Remove whitespaces
        $extensionSettings = array_map('trim', $extensionSettings);

        // remove empty values
        return array_filter($extensionSettings);
    }

    private function createExtConfBuilder(): ExtConfBuilder
    {
        return $this->extConfBuilderFactory->createBuilder();
    }
}
