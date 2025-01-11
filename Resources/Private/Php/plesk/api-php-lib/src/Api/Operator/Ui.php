<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Ui as Struct;

class Ui extends \PleskX\Api\Operator
{
    /**
     * @return array
     */
    public function getNavigation()
    {
        $response = $this->request('get-navigation');

        return unserialize(base64_decode($response->navigation));
    }

    /**
     * @param string $owner
     * @param array $properties
     *
     * @return int
     */
    public function createCustomButton($owner, $properties)
    {
        $packet = $this->_client->getPacket();
        $buttonNode = $packet->addChild($this->_wrapperTag)->addChild('create-custombutton');
        $buttonNode->addChild('owner')->addChild($owner);
        $propertiesNode = $buttonNode->addChild('properties');

        foreach ($properties as $name => $value) {
            $propertiesNode->{$name} = $value;
        }

        $response = $this->_client->request($packet);

        return (int)$response->id;
    }

    /**
     * @param int $id
     *
     * @return Struct\CustomButton
     */
    public function getCustomButton($id)
    {
        $response = $this->request("get-custombutton.filter.custombutton-id=$id");

        return new Struct\CustomButton($response);
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function deleteCustomButton($id)
    {
        return $this->_delete('custombutton-id', $id, 'delete-custombutton');
    }
}
