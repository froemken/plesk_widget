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
use StefanFroemken\PleskWidget\Configuration\ExtConf;
use StefanFroemken\PleskWidget\Plesk\Webspace\Limits;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;

class PleskDataProvider implements ChartDataProviderInterface
{
    protected ExtConf $extConf;

    protected Client $pleskClient;

    public function __construct(ExtConf $extConf)
    {
        $this->extConf = $extConf;
        $this->pleskClient = new Client($this->extConf->getHost(), $this->extConf->getPort());
        $this->pleskClient->setCredentials($this->extConf->getUsername(), $this->extConf->getPassword());
    }

    public function getChartData(): array
    {
        return [
            'labels' => [
                0 => 'HTTP',
                1 => 'Database',
                2 => 'Logs',
                3 => 'Free'
            ],
            'datasets' => [
                [
                    'backgroundColor' => WidgetApi::getDefaultChartColors(),
                    'border' => 0,
                    'data' => $this->getWebSpaceStatus()
                ]
            ]
        ];
    }

    public function getHosting(): array
    {
        $hostingInfo = $this->pleskClient->site()->getHosting(null, null);

        return $hostingInfo->properties;
    }

    public function getCustomer(): \PleskX\Api\Struct\Customer\GeneralInfo
    {
        $customers = $this->pleskClient->customer()->getAll();

        return current($customers);
    }

    protected function getWebSpaceStatus(): array
    {
        $diskUsage = $this->getDiskUsage();
        $diskSpace = (int)$this->getLimit('disk_space')->value;

        return [
            0 => $this->calcDiskUsage(
                ($diskUsage->httpdocs + $diskUsage->httpsdocs),
                $diskSpace
            ),
            1 => $this->calcDiskUsage(
                $diskUsage->dbases,
                $diskSpace
            ),
            2 => $this->calcDiskUsage(
                $diskUsage->logs,
                $diskSpace
            ),
            3 => $this->calcDiskUsage(
                ($diskSpace - $diskUsage->httpdocs + $diskUsage->httpsdocs + $diskUsage->dbases + $diskUsage->logs),
                $diskSpace
            )
        ];
    }

    protected function calcDiskUsage(int $part, int $total = 0): float
    {
        if ($this->extConf->getDiskUsageType() === '%' && $total !== 0) {
            $value = round(100 / $total * $part, 4);
        } elseif ($this->extConf->getDiskUsageType() === 'MB') {
            $value = round($part / 1024 / 1024, 4);
        } elseif ($this->extConf->getDiskUsageType() === 'GB') {
            $value = round($part / 1024 / 1024 / 1024, 4);
        } else {
            $value = (float)$part;
        }

        return $value;
    }

    protected function getLimit(string $limit): \StefanFroemken\PleskWidget\Plesk\Webspace\Limit
    {
        return $this->getLimits()->limits[$limit];
    }

    public function getLoginLink(): string
    {
        // Get external IP address. Works also within DDEV/Docker containers
        $externalIpAddress = file_get_contents('http://ipecho.net/plain');

        if (GeneralUtility::validIP($externalIpAddress)) {
            return sprintf(
                '%s://%s:%d/enterprise/rsession_init.php?PLESKSESSID=%s&success_redirect_url=%s',
                $this->pleskClient->getProtocol() ?: 'https',
                $this->pleskClient->getHost(),
                $this->pleskClient->getPort(),
                $this->pleskClient->server()->createSession($this->extConf->getUsername(), $externalIpAddress),
                '/smb/web/view'
            );
        }

        return sprintf(
            '%s://%s:%d',
            $this->pleskClient->getProtocol() ?: 'https',
            $this->pleskClient->getHost(),
            $this->pleskClient->getPort()
        );
    }

    public function getLimits(): \StefanFroemken\PleskWidget\Plesk\Webspace\Limits
    {
        $packet = $this->pleskClient->getPacket();
        $getTag = $packet->addChild('webspace')->addChild('get');
        $getTag->addChild('filter');
        $getTag->addChild('dataset')->addChild('limits');
        $response = $this->pleskClient->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Limits($xmlResult->data->limits);
        }
        return $items[0];
    }

    public function getSite(): \StefanFroemken\PleskWidget\Plesk\Site
    {
        $packet = $this->pleskClient->getPacket();
        $getTag = $packet->addChild('site')->addChild('get');
        $getTag->addChild('filter');
        $getTag->addChild('dataset')->addChild('gen_info');
        $response = $this->pleskClient->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        $sites = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $site = new \StefanFroemken\PleskWidget\Plesk\Site($xmlResult);
            $site->genInfo = new \StefanFroemken\PleskWidget\Plesk\Site\GeneralInfo($xmlResult->data->gen_info);
            $sites[] = $site;
        }
        return $sites[0];
    }

    public function getDiskUsage(): \PleskX\Api\Struct\Webspace\DiskUsage
    {
        return $this->pleskClient->webspace()->getDiskUsage(null, null);
    }
}
