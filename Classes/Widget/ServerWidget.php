<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Widget;

use PleskX\Api\Client;
use Psr\Http\Message\ServerRequestInterface;
use StefanFroemken\PleskWidget\Client\ExtensionSettingException;
use StefanFroemken\PleskWidget\Client\PleskClientFactory;
use StefanFroemken\PleskWidget\Configuration\ExtConf;
use StefanFroemken\PleskWidget\DataProvider\ServerDataProvider;
use StefanFroemken\PleskWidget\Service\PleskSiteService;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\Provider\ButtonProvider;
use TYPO3\CMS\Dashboard\Widgets\RequestAwareWidgetInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;

class ServerWidget implements WidgetInterface, RequestAwareWidgetInterface
{
    private ServerRequestInterface $request;

    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly BackendViewFactory $backendViewFactory,
        private readonly ServerDataProvider $dataProvider,
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
            $externalIpAddress = $this->getExternalIpAddress();

            $variables['customer'] = $this->dataProvider->getCustomer($pleskClient);
            $variables['externalIpAddress'] = $externalIpAddress;
            $variables['button'] = $this->getButtonProvider($pleskClient, $externalIpAddress);

            if (
                ($domain = $this->extConf->getDomain())
                && ($site = $this->pleskSiteService->getSiteByName($domain, $pleskClient))
                && $ipAddresses = $site->getHosting()->getIpAddresses()
            ) {
                $variables['ipAddresses'] = $ipAddresses;
            }
        } catch (ExtensionSettingException $extensionSettingException) {
            $variables['error'] = $extensionSettingException->getMessage();
        }

        $view->assignMultiple($variables);

        return $view->render('Widget/Server');
    }

    protected function getButtonProvider(Client $pleskClient, string $externalIpAddress): ButtonProvider
    {
        return GeneralUtility::makeInstance(
            ButtonProvider::class,
            'Login to Plesk',
            $this->dataProvider->getLoginLink($pleskClient, $externalIpAddress),
            '_blank'
        );
    }

    private function getExternalIpAddress(): string
    {
        // Get external IP address. Works also within DDEV/Docker containers
        return file_get_contents('http://ipecho.net/plain');
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
