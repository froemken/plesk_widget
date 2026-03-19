<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Widget;

use StefanFroemken\PleskWidget\Client\ExtensionSettingException;
use StefanFroemken\PleskWidget\Client\PleskClientFactory;
use StefanFroemken\PleskWidget\Service\PleskSiteService;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetContext;
use TYPO3\CMS\Dashboard\Widgets\WidgetRendererInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetResult;

class PhpWidget implements WidgetRendererInterface
{
    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly BackendViewFactory $backendViewFactory,
        private readonly PleskClientFactory $pleskClientFactory,
        private readonly PleskSiteService $pleskSiteService,
    ) {}

    public function getSettingsDefinitions(): array
    {
        return [];
    }

    public function renderWidget(WidgetContext $context): WidgetResult
    {
        $view = $this->backendViewFactory->create($context->request);

        $variables = [
            'configuration' => $context->configuration,
        ];

        try {
            $pleskClient = $this->pleskClientFactory->create();
            $domain = $this->extConf->getDomain();

            if ($domain === '') {
                $variables['error'] = 'You have to select a domain name in extension settings of EXT:plesk-widget.';
            } elseif ($site = $this->pleskSiteService->getSiteByName($domain, $pleskClient)) {
                $variables['domain'] = $domain;
                $variables['hosting'] = $site->getHosting();
            } else {
                $variables['error'] = 'Plesk API can not retrieve domain information for ' . $domain;
            }
        } catch (ExtensionSettingException $extensionSettingException) {
            $variables['error'] = $extensionSettingException->getMessage();
        }

        $view->assignMultiple($variables);

        return new WidgetResult(
            content: $view->render('Widget/Php'),
            label: $context->configuration->getTitle(),
            refreshable: true,
        );
    }
}
