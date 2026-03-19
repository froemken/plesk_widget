<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Widget;

use StefanFroemken\PleskWidget\DataProvider\WebspaceDataProvider;
use StefanFroemken\PleskWidget\Service\PleskServerRecordService;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Domain\Record;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Settings\SettingDefinition;
use TYPO3\CMS\Dashboard\Widgets\AdditionalCssInterface;
use TYPO3\CMS\Dashboard\Widgets\EventDataInterface;
use TYPO3\CMS\Dashboard\Widgets\JavaScriptInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetContext;
use TYPO3\CMS\Dashboard\Widgets\WidgetRendererInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetResult;

class WebspaceWidget implements WidgetRendererInterface, EventDataInterface, JavaScriptInterface, AdditionalCssInterface
{
    /**
     * Stateless is not possible here, as we need this widget context in getEventData later
     *
     * @var WidgetContext|null
     */
    private ?WidgetContext $widgetContext = null;

    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly PleskServerRecordService $pleskServerRecordService,
        private readonly BackendViewFactory $backendViewFactory,
        private readonly WebspaceDataProvider $dataProvider,
    ) {}

    public function getSettingsDefinitions(): array
    {
        return [
            new SettingDefinition(
                key: 'pleskServer',
                type: 'int',
                default: 0,
                label: 'plesk_widget.widget:pleskServer',
                description: 'plesk_widget.widget:pleskServer.description',
                enum: $this->pleskServerRecordService->getPleskServerRecordsEnum(),
            ),
            new SettingDefinition(
                key: 'unit',
                type: 'string',
                default: '%',
                label: 'plesk_widget.widget:unit',
                description: 'plesk_widget.widget:unit.description',
                enum: [
                    '%' => 'Percentage',
                    'mb' => 'MegaByte',
                    'gb' => 'GigaByte',
                ],
            ),
        ];
    }

    public function renderWidget(WidgetContext $context): WidgetResult
    {
        $this->widgetContext = $context;

        $error = '';
        $pleskServerRecordUid = (int)$context->settings->get('pleskServer');

        if ($pleskServerRecordUid === 0) {
            $error = 'Please use the configuration icon to select a Plesk server record '
                . 'created on the TYPO3 root page (ID 0).';
        } elseif (
            ($pleskServerRecord = $this->pleskServerRecordService->getPleskServerRecord($pleskServerRecordUid))
            && !$pleskServerRecord instanceof Record
        ) {
            $error = 'The selected Plesk server record (ID: ' . $pleskServerRecordUid . ') was not found.';
        }

        $view = $this->backendViewFactory->create($context->request);
        $view->assignMultiple([
            'configuration' => $context->configuration,
            'error' => $error,
        ]);

        return new WidgetResult(
            content: $view->render('Widget/Webspace'),
            label: $context->configuration->getTitle(),
            refreshable: true,
        );
    }

    public function getEventData(): array
    {
        $pleskServerRecord = $this->pleskServerRecordService->getPleskServerRecord(
            (int)$this->widgetContext->settings->get('pleskServer'),
        );

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
                            'text' => 'Usage in ' . DisplayUnit::from($this->widgetContext->settings->get('unit'))->value,
                        ],
                        'tooltip' => [
                            'enabled' => true,
                        ],
                    ],
                    'cutoutPercentage' => 60,
                ],
                'data' => $this->dataProvider->getChartData($pleskServerRecord, $this->widgetContext),
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
}
