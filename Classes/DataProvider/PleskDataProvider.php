<?php

declare(strict_types = 1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\DataProvider;

use PleskX\Api\Client;
use PleskX\Api\XmlResponse;
use StefanFroemken\PleskWidget\Configuration\ExtConf;
use StefanFroemken\PleskWidget\Plesk\Webspace\Limits;
use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;

class PleskDataProvider implements ChartDataProviderInterface
{
    /**
     * @var ExtConf
     */
    protected $extConf;

    /**
     * @var Client
     */
    protected $pleskClient;

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

    public function getCustomer(): \PleskX\Api\Struct\Customer\GeneralInfo
    {
        $customers = $this->pleskClient->customer()->getAll();

        return current($customers);
    }

    protected function getWebSpaceStatus(): array
    {
        $diskUsage = $this->getDiskUsage();
        $httpUsage = $diskUsage->httpdocs + $diskUsage->httpsdocs;
        $logUsage = $diskUsage->logs;
        $dbUsage = $diskUsage->dbases;
        $diskSpace = $this->getLimit('disk_space')->value;
        return [
            0 => $httpUsage,
            1 => $dbUsage,
            2 => $logUsage,
            3 => $diskSpace - $httpUsage - $dbUsage - $logUsage
        ];
    }

    protected function getLimit(string $limit): \StefanFroemken\PleskWidget\Plesk\Webspace\Limit
    {
        return $this->getLimits()->limits[$limit];
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

    public function getDiskUsage(): \PleskX\Api\Struct\Webspace\DiskUsage
    {
        return $this->pleskClient->webspace()->getDiskUsage(null, null);
    }
}
