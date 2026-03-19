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
use StefanFroemken\PleskWidget\Plesk\Webspace\Limits;
use TYPO3\CMS\Core\Domain\Record;
use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\WidgetContext;

readonly class WebspaceDataProvider
{
    public function __construct(
        private PleskClientFactory $pleskClientFactory,
    ) {}

    public function getChartData(?Record $pleskServerRecord, WidgetContext $widgetContext): array
    {
        if (!$pleskServerRecord instanceof Record) {
            return [];
        }

        try {
            $pleskClient = $this->pleskClientFactory->create($pleskServerRecord);
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
                    'data' => $this->getWebSpaceStatus($pleskClient, $widgetContext),
                ],
            ],
        ];
    }

    private function getWebSpaceStatus(Client $pleskClient, WidgetContext $widgetContext): array
    {
        $diskUsage = $this->getDiskUsage($pleskClient);
        $diskSpace = (int)$this->getLimit('disk_space', $pleskClient)->value;

        return [
            0 => $this->calcDiskUsage(
                ($diskUsage->httpdocs + $diskUsage->httpsdocs),
                $diskSpace,
                $widgetContext,
            ),
            1 => $this->calcDiskUsage(
                $diskUsage->dbases,
                $diskSpace,
                $widgetContext,
            ),
            2 => $this->calcDiskUsage(
                $diskUsage->logs,
                $diskSpace,
                $widgetContext,
            ),
            3 => $this->calcDiskUsage(
                ($diskSpace - $diskUsage->httpdocs + $diskUsage->httpsdocs + $diskUsage->dbases + $diskUsage->logs),
                $diskSpace,
                $widgetContext,
            ),
        ];
    }

    private function calcDiskUsage(int $part, int $total, WidgetContext $widgetContext): float
    {
        $diskUsageType = $widgetContext->settings->get('unit');

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
