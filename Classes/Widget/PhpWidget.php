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
use StefanFroemken\PleskWidget\Client\ExtensionSettingException;
use StefanFroemken\PleskWidget\Client\PleskClientFactory;
use StefanFroemken\PleskWidget\Configuration\ExtConf;
use StefanFroemken\PleskWidget\Service\PleskSiteService;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Dashboard\Widgets\RequestAwareWidgetInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;

class PhpWidget implements WidgetInterface, RequestAwareWidgetInterface
{
    private ServerRequestInterface $request;

    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly BackendViewFactory $backendViewFactory,
        private readonly PleskClientFactory $pleskClientFactory,
        private readonly PleskSiteService $pleskSiteService,
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
        $variables = [
            'configuration' => $this->configuration,
        ];

        try {
            $pleskClient = $this->pleskClientFactory->create();

            if ($this->extConf->getDomain() === '') {
                $variables['error'] = 'You have to select a domain name in extension settings of EXT:plesk-widget.';
            } elseif ($site = $this->pleskSiteService->getSiteByName($this->extConf->getDomain(), $pleskClient)) {
                $variables['domain'] = $this->extConf->getDomain();
                $variables['hosting'] = $site->getHosting();
            } else {
                $variables['error'] = 'Plesk API can not retrieve domain information for ' . $this->extConf->getDomain();
            }
        } catch (ExtensionSettingException $extensionSettingException) {
            $variables['error'] = $extensionSettingException->getMessage();
        }

        $view->assignMultiple($variables);

        return $view->render('Widget/Php');
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
