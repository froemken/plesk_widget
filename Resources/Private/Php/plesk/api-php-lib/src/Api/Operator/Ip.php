<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Ip as Struct;

class Ip extends \PleskX\Api\Operator
{
    /**
     * @return Struct\Info[]
     */
    public function get()
    {
        $ips = [];
        $packet = $this->_client->getPacket();
        $packet->addChild($this->_wrapperTag)->addChild('get');
        $response = $this->_client->request($packet);

        foreach ($response->addresses->ip_info as $ipInfo) {
            $ips[] = new Struct\Info($ipInfo);
        }

        return $ips;
    }
}
