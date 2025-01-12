<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Functional\Configuration;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use StefanFroemken\PleskWidget\Builder\ExtConfBuilderFactory;
use StefanFroemken\PleskWidget\Configuration\ExtConfFactory;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case
 */
class ExtConfFactoryTest extends FunctionalTestCase
{
    protected ExtensionConfiguration|MockObject $extensionConfigurationMock;

    protected ExtConfFactory $subject;

    protected array $testExtensionsToLoad = [
        'typo3/cms-dashboard',
        'stefanfroemken/plesk-widget',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->extensionConfigurationMock = $this->createMock(ExtensionConfiguration::class);

        $this->subject = new ExtConfFactory(
            new ExtConfBuilderFactory(),
            $this->extensionConfigurationMock
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->extensionConfigurationMock,
            $this->subject,
        );
        parent::tearDown();
    }

    #[Test]
    public function getHostWillInitiallyReturnEmptyValue(): void
    {
        self::assertSame(
            '',
            $this->subject->createExtConf()->getCredentialsConfiguration()->getHost()
        );
    }

    #[Test]
    public function getHostWillReturnHost(): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('plesk_widget'))
            ->willReturn([
                'host' => 'plesk.example.com',
            ]);

        self::assertSame(
            'plesk.example.com',
            $this->subject->createExtConf()->getCredentialsConfiguration()->getHost()
        );
    }

    #[Test]
    public function getPortWillInitiallyReturnDefaultPort(): void
    {
        self::assertSame(
            8443,
            $this->subject->createExtConf()->getCredentialsConfiguration()->getPort()
        );
    }

    public static function portDataProvider(): array
    {
        return [
            'Test port with string value' => ['1234', 1234],
            'Test port with integer value' => [4321, 4321],
            'Test port with invalid value' => ['Murks', 8443],
        ];
    }

    #[Test]
    #[DataProvider('portDataProvider')]
    public function getPortWillReturnPort(mixed $port, int $expectedPort): void
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
            $this->subject->createExtConf()->getCredentialsConfiguration()->getPort()
        );
    }

    #[Test]
    public function getUsernameWillInitiallyReturnEmptyValue(): void
    {
        self::assertSame(
            '',
            $this->subject->createExtConf()->getCredentialsConfiguration()->getUsername()
        );
    }

    #[Test]
    public function getUsernameWillReturnUsername(): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('plesk_widget'))
            ->willReturn([
                'username' => 'mustermann',
            ]);

        self::assertSame(
            'mustermann',
            $this->subject->createExtConf()->getCredentialsConfiguration()->getUsername()
        );
    }

    #[Test]
    public function getPasswordWillInitiallyReturnEmptyValue(): void
    {
        self::assertSame(
            '',
            $this->subject->createExtConf()->getCredentialsConfiguration()->getPassword()
        );
    }

    #[Test]
    public function getPasswordWillReturnPassword(): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('plesk_widget'))
            ->willReturn([
                'password' => 'very-cryptic',
            ]);

        self::assertSame(
            'very-cryptic',
            $this->subject->createExtConf()->getCredentialsConfiguration()->getPassword()
        );
    }

    #[Test]
    public function getDiskUsageTypeWillInitiallyReturnDefaultValue(): void
    {
        self::assertSame(
            '%',
            $this->subject->createExtConf()->getViewConfiguration()->getDiskUsageType()
        );
    }

    #[Test]
    public function getDiskUsageTypeWillReturnDiskUsageType(): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('plesk_widget'))
            ->willReturn([
                'diskUsageType' => 'MB',
            ]);

        self::assertSame(
            'MB',
            $this->subject->createExtConf()->getViewConfiguration()->getDiskUsageType()
        );
    }

    #[Test]
    public function getDiskUsageWithInvalidValueWillReturnDefaultValue(): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('plesk_widget'))
            ->willReturn([
                'diskUsageType' => 'INVALID',
            ]);

        self::assertSame(
            '%',
            $this->subject->createExtConf()->getViewConfiguration()->getDiskUsageType()
        );
    }

    #[Test]
    public function getDomainWillInitiallyReturnEmptyValue(): void
    {
        self::assertSame(
            '',
            $this->subject->createExtConf()->getViewConfiguration()->getDomain()
        );
    }

    #[Test]
    public function getDomainWillReturnDomain(): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('plesk_widget'))
            ->willReturn([
                'domain' => '134.example.com',
            ]);

        self::assertSame(
            '134.example.com',
            $this->subject->createExtConf()->getViewConfiguration()->getDomain()
        );
    }

    #[Test]
    public function trimValuesBeforeAssigningThem(): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('plesk_widget'))
            ->willReturn([
                'host' => '       plesk.example.com',
                'port' => '   1234',
                'username' => 'mustermann ',
                'password' => '     very-cryptic',
                'diskUsageType' => 'MB',
                'domain' => ' 134.example.com    ',
            ]);

        $extConf = $this->subject->createExtConf();

        self::assertSame(
            [
                'host' => 'plesk.example.com',
                'port' => 1234,
                'username' => 'mustermann',
                'password' => 'very-cryptic',
                'diskUsageType' => 'MB',
                'domain' => '134.example.com',
            ],
            [
                'host' => $extConf->getCredentialsConfiguration()->getHost(),
                'port' => $extConf->getCredentialsConfiguration()->getPort(),
                'username' => $extConf->getCredentialsConfiguration()->getUsername(),
                'password' => $extConf->getCredentialsConfiguration()->getPassword(),
                'diskUsageType' => $extConf->getViewConfiguration()->getDiskUsageType(),
                'domain' => $extConf->getViewConfiguration()->getDomain(),
            ]
        );
    }

    #[Test]
    public function emptyValuesWillBeFilledWithDefaultValues(): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with(self::identicalTo('plesk_widget'))
            ->willReturn([
                'port' => '',
                'diskUsageType' => '',
            ]);

        $extConf = $this->subject->createExtConf();

        self::assertSame(
            [
                'host' => '',
                'port' => 8443,
                'username' => '',
                'password' => '',
                'diskUsageType' => '%',
                'domain' => '',
            ],
            [
                'host' => $extConf->getCredentialsConfiguration()->getHost(),
                'port' => $extConf->getCredentialsConfiguration()->getPort(),
                'username' => $extConf->getCredentialsConfiguration()->getUsername(),
                'password' => $extConf->getCredentialsConfiguration()->getPassword(),
                'diskUsageType' => $extConf->getViewConfiguration()->getDiskUsageType(),
                'domain' => $extConf->getViewConfiguration()->getDomain(),
            ]
        );
    }
}
