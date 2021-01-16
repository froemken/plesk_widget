<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Configuration;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/*
 * This class streamlines all settings from extension manager
 */
class ExtConf implements SingletonInterface
{
    /**
     * @var string
     */
    protected $diskUsageType = '%';

    /**
     * @var string
     */
    protected $host = '';

    /**
     * @var int
     */
    protected $port = 8443;

    /**
     * @var string
     */
    protected $username = '';

    /**
     * @var string
     */
    protected $password = '';

    public function __construct()
    {
        // get global configuration
        $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('plesk_widget');
        if (is_array($extConf) && count($extConf)) {
            // call setter method foreach configuration entry
            foreach ($extConf as $key => $value) {
                $methodName = 'set' . ucfirst($key);
                if (method_exists($this, $methodName)) {
                    $this->$methodName($value);
                }
            }
        }
    }

    public function getDiskUsageType(): string
    {
        return $this->diskUsageType;
    }

    public function setDiskUsageType(string $diskUsageType): void
    {
        $this->diskUsageType = $diskUsageType;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(string $port): void
    {
        $this->port = (int)$port;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
