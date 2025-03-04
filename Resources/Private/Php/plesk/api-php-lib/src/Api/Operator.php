<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api;

class Operator
{
    /** @var string|null */
    protected $_wrapperTag;

    /** @var \PleskX\Api\Client */
    protected $_client;

    public function __construct($client)
    {
        $this->_client = $client;

        if (is_null($this->_wrapperTag)) {
            $classNameParts = explode('\\', static::class);
            $this->_wrapperTag = end($classNameParts);
            $this->_wrapperTag = strtolower(preg_replace('/([a-z])([A-Z])/', '\1-\2', $this->_wrapperTag));
        }
    }

    /**
     * Perform plain API request.
     *
     * @param string|array $request
     * @param int $mode
     *
     * @return XmlResponse
     */
    public function request($request, $mode = Client::RESPONSE_SHORT)
    {
        $wrapperTag = $this->_wrapperTag;

        if (is_array($request)) {
            $request = [$wrapperTag => $request];
        } elseif (preg_match('/^[a-z]/', $request)) {
            $request = "$wrapperTag.$request";
        } else {
            $request = "<$wrapperTag>$request</$wrapperTag>";
        }

        return $this->_client->request($request, $mode);
    }

    /**
     * @param string $field
     * @param int|string $value
     * @param string $deleteMethodName
     *
     * @return bool
     */
    protected function _delete($field, $value, $deleteMethodName = 'del')
    {
        $response = $this->request([
            $deleteMethodName => [
                'filter' => [
                    $field => $value,
                ],
            ],
        ]);

        return (string)$response->status === 'ok';
    }

    /**
     * @param string $structClass
     * @param string $infoTag
     * @param string|null $field
     * @param int|string|null $value
     */
    protected function _getItems($structClass, $infoTag, $field = null, $value = null, callable $filter = null)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild('get');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->{$field} = $value;
        }

        $getTag->addChild('dataset')->addChild($infoTag);

        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            if (!is_null($filter) && !$filter($xmlResult->data->$infoTag)) {
                continue;
            }
            if (!isset($xmlResult->data) || !isset($xmlResult->data->$infoTag)) {
                continue;
            }
            $items[] = new $structClass($xmlResult->data->$infoTag);
        }

        return $items;
    }
}
