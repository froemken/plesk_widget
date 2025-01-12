<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Tests\Functional\Configuration;

use PHPUnit\Framework\Attributes\Test;
use StefanFroemken\PleskWidget\Configuration\DiskUsageTypeEnum;
use StefanFroemken\PleskWidget\Configuration\ExtConf;
use TypeError;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case
 */
class ExtConfTest extends FunctionalTestCase
{
    protected ExtConf $subject;

    protected array $testExtensionsToLoad = [
        'typo3/cms-dashboard',
        'stefanfroemken/plesk-widget',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $extensionSetting = [
            'host' => 'plesk.example.com',
            'port' => 1234,
            'username' => 'mustermann',
            'password' => 'very-cryptic',
            'diskUsageType' => DiskUsageTypeEnum::from('MB'),
            'domain' => '134.example.com',
        ];

        $this->subject = new ExtConf($extensionSetting);
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
    public function getHostWillReturnHost(): void
    {
        self::assertSame(
            'plesk.example.com',
            $this->subject->getHost()
        );
    }

    #[Test]
    public function getPortWillReturnPortAsInt(): void
    {
        self::assertSame(
            1234,
            $this->subject->getPort()
        );
    }

    #[Test]
    public function getUsernameWillReturnUsername(): void
    {
        self::assertSame(
            'mustermann',
            $this->subject->getUsername()
        );
    }

    #[Test]
    public function getPasswordWillReturnPassword(): void
    {
        self::assertSame(
            'very-cryptic',
            $this->subject->getPassword()
        );
    }

    #[Test]
    public function getDiskUsageTypeWillReturnDiskUsageType(): void
    {
        self::assertSame(
            'MB',
            $this->subject->getDiskUsageType()
        );
    }

    #[Test]
    public function getDiskUsageWithInvalidValueWillThrowTypeError(): void
    {
        self::expectException(TypeError::class);

        $subject = new ExtConf([
            'diskUsageType' => 'INVALID',
        ]);

        self::assertSame(
            'WillNotBeTested',
            $subject->getDiskUsageType()
        );
    }

    #[Test]
    public function getDomainWillReturnDomain(): void
    {
        self::assertSame(
            '134.example.com',
            $this->subject->getDomain()
        );
    }
}
