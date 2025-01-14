<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\DataProvider;

use PleskX\Api\Client;
use PleskX\Api\Struct\Customer\GeneralInfo;
use StefanFroemken\PleskWidget\Configuration\ExtConf;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ServerDataProvider
{
    public function __construct(private readonly ExtConf $extConf) {}

    public function getCustomer(Client $pleskClient): GeneralInfo
    {
        $customers = $pleskClient->customer()->getAll();

        return current($customers);
    }

    public function getLoginLink(Client $pleskClient, string $externalIpAddress): string
    {
        if (GeneralUtility::validIP($externalIpAddress)) {
            // Return direct login link
            return sprintf(
                '%s://%s:%d/enterprise/rsession_init.php?PLESKSESSID=%s&success_redirect_url=%s',
                $pleskClient->getProtocol() ?: 'https',
                $pleskClient->getHost(),
                $pleskClient->getPort(),
                $pleskClient->server()->createSession(
                    $this->extConf->getUsername(),
                    $externalIpAddress
                ),
                '/smb/web/view'
            );
        }

        // Return link to login form
        return sprintf(
            '%s://%s:%d',
            $pleskClient->getProtocol() ?: 'https',
            $pleskClient->getHost(),
            $pleskClient->getPort()
        );
    }
}
