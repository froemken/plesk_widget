<?php

declare(strict_types = 1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\DataProvider;

use StefanFroemken\PleskWidget\Configuration\ExtConf;
use TYPO3\CMS\Dashboard\WidgetApi;
use TYPO3\CMS\Dashboard\Widgets\ChartDataProviderInterface;

class PleskDataProvider implements ChartDataProviderInterface
{
    /**
     * @var ExtConf
     */
    protected $extConf;

    public function __construct(ExtConf $extConf)
    {
        $this->extConf = $extConf;
    }

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
        $client = new \PleskX\Api\Client($this->extConf->getHost(), $this->extConf->getPort());
        $client->setCredentials($this->extConf->getUsername(), $this->extConf->getPassword());

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

    protected function getLimits()
    {
        //$client->webspace()->getLimits(null, null)
    }
}
