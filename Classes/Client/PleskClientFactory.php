<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Client;

use PleskX\Api\Client;
use Psr\Log\LoggerInterface;
use StefanFroemken\PleskWidget\Configuration\ExtConf;

class PleskClientFactory
{
    public function __construct(
        private readonly ExtConf $extConf,
        private readonly LoggerInterface $logger
    ) {}

    /**
     * @throws ExtensionSettingException
     */
    public function create(): Client
    {
        if ($this->validateExtConf() === false) {
            throw new ExtensionSettingException(
                'Incomplete plesk widget extension settings. See logs for more details',
                1736610959
            );
        }

        $pleskClient = new Client($this->extConf->getHost(), $this->extConf->getPort());
        $pleskClient->setCredentials($this->extConf->getUsername(), $this->extConf->getPassword());

        return $pleskClient;
    }

    private function validateExtConf(): bool
    {
        if ($this->extConf->getHost() === '') {
            $this->logger->error('Plesk host in extension settings can not be empty');
            return false;
        }

        if ($this->extConf->getUsername() === '') {
            $this->logger->error('Plesk user in extension settings can not be empty');
            return false;
        }

        if ($this->extConf->getPassword() === '') {
            $this->logger->error('Plesk password in extension settings can not be empty');
            return false;
        }

        return true;
    }
}
