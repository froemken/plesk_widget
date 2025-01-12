<?php

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
            $this->subject->create()->getHost()
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
            $this->subject->create()->getHost()
        );
    }

    #[Test]
    public function getPortWillInitiallyReturnDefaultPort(): void
    {
        self::assertSame(
            8443,
            $this->subject->create()->getPort()
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
            $this->subject->create()->getPort()
        );
    }

    #[Test]
    public function getUsernameWillInitiallyReturnEmptyValue(): void
    {
        self::assertSame(
            '',
            $this->subject->create()->getUsername()
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
            $this->subject->create()->getUsername()
        );
    }

    #[Test]
    public function getPasswordWillInitiallyReturnEmptyValue(): void
    {
        self::assertSame(
            '',
            $this->subject->create()->getPassword()
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
            $this->subject->create()->getPassword()
        );
    }

    #[Test]
    public function getDiskUsageTypeWillInitiallyReturnDefaultValue(): void
    {
        self::assertSame(
            '%',
            $this->subject->create()->getDiskUsageType()
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
            $this->subject->create()->getDiskUsageType()
        );
    }

    #[Test]
    public function getDomainWillInitiallyReturnEmptyValue(): void
    {
        self::assertSame(
            '',
            $this->subject->create()->getDomain()
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
            $this->subject->create()->getDomain()
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

        $extConf = $this->subject->create();

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
                'host' => $extConf->getHost(),
                'port' => $extConf->getPort(),
                'username' => $extConf->getUsername(),
                'password' => $extConf->getPassword(),
                'diskUsageType' => $extConf->getDiskUsageType(),
                'domain' => $extConf->getDomain(),
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

        $extConf = $this->subject->create();

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
                'host' => $extConf->getHost(),
                'port' => $extConf->getPort(),
                'username' => $extConf->getUsername(),
                'password' => $extConf->getPassword(),
                'diskUsageType' => $extConf->getDiskUsageType(),
                'domain' => $extConf->getDomain(),
            ]
        );
    }
}
