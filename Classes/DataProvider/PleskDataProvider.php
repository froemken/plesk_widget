<?php

declare(strict_types = 1);

/*
 * This file is part of the package stefanfroemken/mysql-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\DataProvider;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;

class PleskDataProvider implements ChartDataProviderInterface
{
    public function getChartData(): array
    {
        return [
            'labels' => [
                0 => 'Used',
                1 => 'Misc',
                2 => 'Free'
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

    protected function getWebSpaceStatus()
    {
        $client = new \PleskX\Api\Client('900410.jweiland-hosting.de');
        $client->setCredentials('froemkenl', 'vfM!BiX6V2$5^cDP');

        //$client->customer()->getAll();

        // Seems to be just the HardDisk, but no Usage
        /*var_dump(
            $client->webspace()->getAll()
        );*/
        /*var_dump(
            $client->webspace()->getDiskUsage(null, null)
        );*/

        // Limits. Max disk space. Soft limits. mbox limits
        /*var_dump(
            $client->webspace()->getLimits(null, null)
        );*/

        /*var_dump(
            $client->database()->getAllUsers()
        );*/
        /*return [
            0 => 25,
            1 => 35,
            2 => 40
        ];*/
        return $client->customer()->getAll();
    }
}
