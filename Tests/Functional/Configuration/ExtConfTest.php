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
use StefanFroemken\PleskWidget\Configuration\CredentialsConfiguration;
use StefanFroemken\PleskWidget\Configuration\DiskUsageTypeEnum;
use StefanFroemken\PleskWidget\Configuration\ExtConf;
use StefanFroemken\PleskWidget\Configuration\ViewConfiguration;
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

        $this->subject = new ExtConf(
            new CredentialsConfiguration(
                'plesk.example.com',
                1234,
                'mustermann',
                'very-cryptic',
            ),
            new ViewConfiguration(
                DiskUsageTypeEnum::from('MB'),
                '134.example.com',
            )
        );
    }

    protected function tearDown(): void
    {
        unset(
            $this->subject,
        );

        parent::tearDown();
    }

    #[Test]
    public function getHostWillReturnHost(): void
    {
        self::assertSame(
            'plesk.example.com',
            $this->subject->getCredentialsConfiguration()->getHost()
        );
    }

    #[Test]
    public function getPortWillReturnPortAsInt(): void
    {
        self::assertSame(
            1234,
            $this->subject->getCredentialsConfiguration()->getPort()
        );
    }

    #[Test]
    public function getUsernameWillReturnUsername(): void
    {
        self::assertSame(
            'mustermann',
            $this->subject->getCredentialsConfiguration()->getUsername()
        );
    }

    #[Test]
    public function getPasswordWillReturnPassword(): void
    {
        self::assertSame(
            'very-cryptic',
            $this->subject->getCredentialsConfiguration()->getPassword()
        );
    }

    #[Test]
    public function getDiskUsageTypeWillReturnDiskUsageType(): void
    {
        self::assertSame(
            'MB',
            $this->subject->getViewConfiguration()->getDiskUsageType()
        );
    }

    #[Test]
    public function getDomainWillReturnDomain(): void
    {
        self::assertSame(
            '134.example.com',
            $this->subject->getViewConfiguration()->getDomain()
        );
    }
}
