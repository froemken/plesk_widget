<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Widgets;

use StefanFroemken\PleskWidget\Configuration\ExtConf;
use StefanFroemken\PleskWidget\DataProvider\PleskDataProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\AdditionalCssInterface;
use TYPO3\CMS\Dashboard\Widgets\EventDataInterface;
use TYPO3\CMS\Dashboard\Widgets\Provider\ButtonProvider;
use TYPO3\CMS\Dashboard\Widgets\RequireJsModuleInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class Webspace implements WidgetInterface, EventDataInterface, AdditionalCssInterface, RequireJsModuleInterface
{
    private WidgetConfigurationInterface $configuration;

    private StandaloneView $view;

    private PleskDataProvider $dataProvider;

    private ExtConf $extConf;

    private bool $hasError = false;

    public function __construct(
        WidgetConfigurationInterface $configuration,
        StandaloneView $view,
        PleskDataProvider $dataProvider,
        ExtConf $extConf
    ) {
        $this->configuration = $configuration;
        $this->view = $view;
        $this->dataProvider = $dataProvider;
        $this->extConf = $extConf;
    }

    public function renderWidgetContent(): string
    {
        try {
            if ($this->extConf->getUsername() === '' || $this->extConf->getPassword() === '') {
                $this->hasError = true;
                $variables = [
                    'error' => 'No username or password given. Please configure authentication in extension settings of EXT:plesk_widget'
                ];
            } else {
                $variables = [
                    'configuration' => $this->configuration,
                    'customer' => $this->dataProvider->getCustomer(),
                    'hosting' => $this->dataProvider->getHosting(),
                    'site' => $this->dataProvider->getSite(),
                    'button' => $this->getButtonProvider()
                ];
            }
        } catch (\Exception $exception) {
            $this->hasError = true;
            $variables = [
                'error' => $exception->getMessage()
            ];
        }

        $this->view->setTemplate('Widget/Webspace');
        $this->view->assign('configuration', $this->configuration);
        $this->view->assignMultiple($variables);

        return $this->view->render();
    }

    public function getEventData(): array
    {
        if ($this->hasError) {
            $data = '{}';
        } else {
            $data = $this->dataProvider->getChartData();
        }

        return [
            'graphConfig' => [
                'type' => 'doughnut',
                'options' => [
                    //'maintainAspectRatio' => false,
                    'legend' => [
                        'display' => true,
                        'position' => 'bottom'
                    ],
                    'tooltips' => [
                        'enabled' => true
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Usage in ' . $this->extConf->getDiskUsageType()
                    ]
                ],
                'data' => $data,
            ],
        ];
    }

    protected function getButtonProvider(): ButtonProvider
    {
        return GeneralUtility::makeInstance(
            ButtonProvider::class,
            'Login to Plesk',
            $this->dataProvider->getLoginLink(),
            '_blank'
        );
    }

    public function getCssFiles(): array
    {
        return [
            'EXT:dashboard/Resources/Public/Css/Contrib/chart.css'
        ];
    }

    public function getRequireJsModules(): array
    {
        return [
            'TYPO3/CMS/Dashboard/Contrib/chartjs',
            'TYPO3/CMS/Dashboard/ChartInitializer',
        ];
    }
}
