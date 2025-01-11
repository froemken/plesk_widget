<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Service;

use PleskX\Api\Client;
use StefanFroemken\PleskWidget\Plesk\Site;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;

class PleskSiteService
{
    public function __construct(private readonly FrontendInterface $cache) {}

    /**
     * @return Site[]
     */
    public function getSites(Client $pleskClient): array
    {
        // Early return, if sites are already collected
        if ($this->cache->has('plesk-widget-sites')) {
            return $this->cache->get('plesk-widget-sites');
        }

        $sites = [];
        foreach ($pleskClient->site()->getAll() as $generalInfo) {
            $sites[] = new Site($pleskClient, $generalInfo);
        }

        $this->cache->set('plesk-widget-sites', $sites);

        return $sites;
    }

    public function getSiteByName(string $name, Client $pleskClient): ?Site
    {
        foreach ($this->getSites($pleskClient) as $site) {
            if ($site->getGeneralInformation()->name === $name) {
                return $site;
            }
        }

        return null;
    }
}
