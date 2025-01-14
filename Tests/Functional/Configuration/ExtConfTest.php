<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Tests\Functional\Configuration;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use StefanFroemken\PleskWidget\Configuration\DiskUsageTypeEnum;
use StefanFroemken\PleskWidget\Configuration\ExtConf;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case
 */
class ExtConfTest extends FunctionalTestCase
{
    protected ExtensionConfiguration|MockObject $extensionConfigurationMock;

    protected array $testExtensionsToLoad = [
        'typo3/cms-dashboard',
        'stefanfroemken/plesk-widget',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->extensionConfigurationMock = $this->createMock(ExtensionConfiguration::class);
    }

    protected function tearDown(): void
    {
        unset(
            $this->extensionConfigurationMock,
        );

        parent::tearDown();
    }

    public static function defaultValueDataProvider(): array
    {
        return [
            'expect default host to be empty' => ['getHost', ''],
            'expect default port to be 8443' => ['getPort', 8443],
            'expect default username to be empty' => ['getUsername', ''],
            'expect default password to be empty' => ['getPassword', ''],
            'expect default diskUsageType to be percent' => ['getDiskUsageType', DiskUsageTypeEnum::from('%')],
            'expect default domain to be empty' => ['getDomain', ''],
        ];
    }

    #[Test]
    #[DataProvider('defaultValueDataProvider')]
    public function extConfGettersWillInitiallyReturnDefaultValues(string $method, mixed $expectedDefaultValue): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('plesk_widget'))
            ->willReturn([]);

        self::assertSame(
            $expectedDefaultValue,
            ExtConf::create($this->extensionConfigurationMock)->{$method}()
        );
    }

    public static function extensionSettingsDataProvider(): array
    {
        return [
            'expect given host to be plesk.exmaple.com' => ['getHost', 'plesk.example.com'],
            'expect given port to be 1234' => ['getPort', 1234],
            'expect given username to be mustermann' => ['getUsername', 'mustermann'],
            'expect given password to be very-cryptic' => ['getPassword', 'very-cryptic'],
            'expect given diskUsageType to be MB' => ['getDiskUsageType', DiskUsageTypeEnum::from('MB')],
            'expect given domain to be example.com' => ['getDomain', 'example.com'],
        ];
    }

    #[Test]
    #[DataProvider('extensionSettingsDataProvider')]
    public function extConfGettersWillReturnValuesFromExtConf(string $method, mixed $expectedDefaultValue): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('plesk_widget'))
            ->willReturn([
                'host' => 'plesk.example.com',
                'port' => 1234,
                'username' => 'mustermann',
                'password' => 'very-cryptic',
                'diskUsageType' => 'MB',
                'domain' => 'example.com',
            ]);

        self::assertSame(
            $expectedDefaultValue,
            ExtConf::create($this->extensionConfigurationMock)->{$method}()
        );
    }

    public static function portDataProvider(): array
    {
        return [
            'Port as string will be casted to integer' => ['5378', 5378],
            'Invalid port will return default port' => ['Hello World!', 8443],
            'Empty port will return default port' => ['', 8443],
            'Zero port will return default port' => [0, 8443],
        ];
    }

    #[Test]
    #[DataProvider('portDataProvider')]
    public function getPortWillReturnExpectedValues(mixed $port, int $expectedPort): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('plesk_widget'))
            ->willReturn([
                'port' => $port,
            ]);

        self::assertSame(
            $expectedPort,
            ExtConf::create($this->extensionConfigurationMock)->getPort()
        );
    }

    public static function diskUsageTypeDataProvider(): array
    {
        return [
            'DiskUsageType with MB' => ['MB', 'MB'],
            'DiskUsageType with GB' => ['GB', 'GB'],
            'DiskUsageType with %' => ['%', '%'],
            'Empty DiskUsageType will return default value' => ['', '%'],
            'Invalid DiskUsageType will return default value' => ['Hello World!', '%'],
        ];
    }

    #[Test]
    #[DataProvider('diskUsageTypeDataProvider')]
    public function getDiskUsageTypeWillReturnExpectedValues(mixed $diskUsageType, string $expectedDiskUsageType): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('plesk_widget'))
            ->willReturn([
                'diskUsageType' => $diskUsageType,
            ]);

        self::assertSame(
            $expectedDiskUsageType,
            ExtConf::create($this->extensionConfigurationMock)->getDiskUsageType()->value
        );
    }
}
