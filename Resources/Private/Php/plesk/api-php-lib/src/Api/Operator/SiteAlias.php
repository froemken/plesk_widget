<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\SiteAlias as Struct;

class SiteAlias extends \PleskX\Api\Operator
{
    /**
     * @return Struct\Info
     */
    public function create(array $properties, array $preferences = [])
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild('create');

        if (count($preferences) > 0) {
            $prefs = $info->addChild('pref');

            foreach ($preferences as $key => $value) {
                $prefs->addChild($key, is_bool($value) ? ($value ? 1 : 0) : $value);
            }
        }

        $info->addChild('site-id', $properties['site-id']);
        $info->addChild('name', $properties['name']);

        $response = $this->_client->request($packet);

        return new Struct\Info($response);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function delete($field, $value)
    {
        return $this->_delete($field, $value, 'delete');
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $items = $this->getAll($field, $value);

        return reset($items);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\GeneralInfo[]
     */
    public function getAll($field = null, $value = null)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild('get');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->{$field} = $value;
        }

        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $item = new Struct\GeneralInfo($xmlResult->info);
            $items[] = $item;
        }

        return $items;
    }
}
