<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Locale as Struct;

class Locale extends \PleskX\Api\Operator
{
    /**
     * @param string|null $id
     *
     * @return Struct\Info|Struct\Info[]
     */
    public function get($id = null)
    {
        $locales = [];
        $packet = $this->_client->getPacket();
        $filter = $packet->addChild($this->_wrapperTag)->addChild('get')->addChild('filter');

        if (!is_null($id)) {
            $filter->addChild('id', $id);
        }

        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        foreach ($response->locale->get->result as $localeInfo) {
            $locales[(string)$localeInfo->info->id] = new Struct\Info($localeInfo->info);
        }

        return !is_null($id) ? reset($locales) : $locales;
    }
}
