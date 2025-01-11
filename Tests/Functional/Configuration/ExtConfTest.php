<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\Glossary2\Tests\Functional\Configuration;

use StefanFroemken\PleskWidget\Configuration\ExtConf;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case
 */
class ExtConfTest extends FunctionalTestCase
{
    protected ExtensionConfiguration|MockObject $extensionConfigurationMock;

    protected ExtConf $subject;

    protected array $testExtensionsToLoad = [
        'stefanfroemken/plesk-widget',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->extensionConfigurationMock = $this->createMock(ExtensionConfiguration::class);

        $this->subject = new ExtConf(
            $this->extensionConfigurationMock
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
    public function getHostWillReturnEmptyHost(): void
    {
        self::assertSame(
            '0-9,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z',
            $this->subject->getPossibleLetters(),
        );
    }
}
