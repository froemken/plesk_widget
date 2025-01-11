<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Widget;

use Psr\Http\Message\ServerRequestInterface;
use StefanFroemken\PleskWidget\Configuration\ExtConf;
use StefanFroemken\PleskWidget\DataProvider\WebspaceDataProvider;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Dashboard\Widgets\AdditionalCssInterface;
use TYPO3\CMS\Dashboard\Widgets\EventDataInterface;
use TYPO3\CMS\Dashboard\Widgets\JavaScriptInterface;
use TYPO3\CMS\Dashboard\Widgets\RequestAwareWidgetInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;

class WebspaceWidget implements WidgetInterface, EventDataInterface, JavaScriptInterface, AdditionalCssInterface, RequestAwareWidgetInterface
{
    private ServerRequestInterface $request;

    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly BackendViewFactory $backendViewFactory,
        private readonly WebspaceDataProvider $dataProvider,
        private readonly ExtConf $extConf,
        private readonly array $options = []
    ) {}

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function renderWidgetContent(): string
    {
        $view = $this->backendViewFactory->create($this->request);

        $view->assign('configuration', $this->configuration);

        return $view->render('Widget/Webspace');
    }

    public function getEventData(): array
    {
        return [
            'graphConfig' => [
                'type' => 'doughnut',
                'options' => [
                    'maintainAspectRatio' => false,
                    'plugins' => [
                        'legend' => [
                            'display' => true,
                            'position' => 'bottom',
                        ],
                        'title' => [
                            'display' => true,
                            'text' => 'Usage in ' . $this->extConf->getDiskUsageType(),
                        ],
                        'tooltip' => [
                            'enabled' => true,
                        ],
                    ],
                    'cutoutPercentage' => 60,
                ],
                'data' => $this->dataProvider->getChartData(),
            ],
        ];
    }

    public function getCssFiles(): array
    {
        return [];
    }

    public function getJavaScriptModuleInstructions(): array
    {
        return [
            JavaScriptModuleInstruction::create('@typo3/dashboard/contrib/chartjs.js'),
            JavaScriptModuleInstruction::create('@typo3/dashboard/chart-initializer.js'),
        ];
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
