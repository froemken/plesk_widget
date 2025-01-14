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
use StefanFroemken\PleskWidget\Client\ExtensionSettingException;
use StefanFroemken\PleskWidget\Client\PleskClientFactory;
use StefanFroemken\PleskWidget\Configuration\ExtConf;
use StefanFroemken\PleskWidget\Plesk\Webspace\Limits;
use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;

class WebspaceDataProvider implements ChartDataProviderInterface
{
    public function __construct(
        private readonly ExtConf $extConf,
        private readonly PleskClientFactory $pleskClientFactory
    ) {}

    public function getChartData(): array
    {
        try {
            $pleskClient = $this->pleskClientFactory->create();
        } catch (ExtensionSettingException $e) {
            return [];
        }

        return [
            'labels' => [
                0 => 'HTTP',
                1 => 'Database',
                2 => 'Logs',
                3 => 'Free',
            ],
            'datasets' => [
                [
                    'backgroundColor' => WidgetApi::getDefaultChartColors(),
                    'border' => 0,
                    'data' => $this->getWebSpaceStatus($pleskClient),
                ],
            ],
        ];
    }

    private function getWebSpaceStatus(Client $pleskClient): array
    {
        $diskUsage = $this->getDiskUsage($pleskClient);
        $diskSpace = (int)$this->getLimit('disk_space', $pleskClient)->value;

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
            ),
        ];
    }

    private function calcDiskUsage(int $part, int $total = 0): float
    {
        $diskUsageType = $this->extConf->getDiskUsageType()->value;

        if ($diskUsageType === '%' && $total !== 0) {
            $value = round(100 / $total * $part, 4);
        } elseif ($diskUsageType === 'MB') {
            $value = round($part / 1024 / 1024, 4);
        } elseif ($diskUsageType === 'GB') {
            $value = round($part / 1024 / 1024 / 1024, 4);
        } else {
            $value = (float)$part;
        }

        return $value;
    }

    private function getLimit(string $limit, Client $pleskClient): \StefanFroemken\PleskWidget\Plesk\Webspace\Limit
    {
        return $this->getLimits($pleskClient)->limits[$limit];
    }

    private function getLimits(Client $pleskClient): \StefanFroemken\PleskWidget\Plesk\Webspace\Limits
    {
        $packet = $pleskClient->getPacket();
        $getTag = $packet->addChild('webspace')->addChild('get');
        $getTag->addChild('filter');
        $getTag->addChild('dataset')->addChild('limits');
        $response = $pleskClient->request($packet, Client::RESPONSE_FULL);

        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Limits($xmlResult->data->limits);
        }
        return $items[0];
    }

    private function getDiskUsage(Client $pleskClient): \PleskX\Api\Struct\Webspace\DiskUsage
    {
        return $pleskClient->webspace()->getDiskUsage(null, null);
    }
}
