<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\EventLog as Struct;

class EventLog extends \PleskX\Api\Operator
{
    protected $_wrapperTag = 'event_log';

    /**
     * @return Struct\Event[]
     */
    public function get()
    {
        $records = [];
        $response = $this->request('get');

        foreach ($response->event as $eventInfo) {
            $records[] = new Struct\Event($eventInfo);
        }

        return $records;
    }

    /**
     * @return Struct\DetailedEvent[]
     */
    public function getDetailedLog()
    {
        $records = [];
        $response = $this->request('get_events');

        foreach ($response->event as $eventInfo) {
            $records[] = new Struct\DetailedEvent($eventInfo);
        }

        return $records;
    }

    /**
     * @return int
     */
    public function getLastId()
    {
        return (int)$this->request('get-last-id')->getValue('id');
    }
}
