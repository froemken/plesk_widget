<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Database as Struct;

class Database extends \PleskX\Api\Operator
{
    /**
     * @param array $properties
     *
     * @return Struct\Info
     */
    public function create($properties)
    {
        return new Struct\Info($this->_process('add-db', $properties));
    }

    /**
     * @param array $properties
     *
     * @return Struct\UserInfo
     */
    public function createUser($properties)
    {
        return new Struct\UserInfo($this->_process('add-db-user', $properties));
    }

    /**
     * @param string $command
     *
     * @return \PleskX\Api\XmlResponse
     */
    private function _process($command, array $properties)
    {
        $packet = $this->_client->getPacket();
        $info = $packet->addChild($this->_wrapperTag)->addChild($command);

        foreach ($properties as $name => $value) {
            if (str_contains($value, '&')) {
                $info->$name = $value;
                continue;
            }
            $info->{$name} = $value;
        }

        return $this->_client->request($packet);
    }

    /**
     * @return bool
     */
    public function updateUser(array $properties)
    {
        $response = $this->_process('set-db-user', $properties);

        return (string)$response->status === 'ok';
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\Info
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
     * @return Struct\UserInfo
     */
    public function getUser($field, $value)
    {
        $items = $this->getAllUsers($field, $value);

        return reset($items);
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\Info[]
     */
    public function getAll($field, $value)
    {
        $response = $this->_get('get-db', $field, $value);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Struct\Info($xmlResult);
        }

        return $items;
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return Struct\UserInfo[]
     */
    public function getAllUsers($field, $value)
    {
        $response = $this->_get('get-db-users', $field, $value);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Struct\UserInfo($xmlResult);
        }

        return $items;
    }

    /**
     * @param string $command
     * @param string $field
     * @param int|string $value
     *
     * @return \PleskX\Api\XmlResponse
     */
    private function _get($command, $field, $value)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild($command);

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->{$field} = $value;
        }

        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        return $response;
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function delete($field, $value)
    {
        return $this->_delete($field, $value, 'del-db');
    }

    /**
     * @param string $field
     * @param int|string $value
     *
     * @return bool
     */
    public function deleteUser($field, $value)
    {
        return $this->_delete($field, $value, 'del-db-user');
    }
}
