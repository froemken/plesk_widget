<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Session as Struct;

class Session extends \PleskX\Api\Operator
{
    /**
     * @return Struct\Info[]
     */
    public function get()
    {
        $sessions = [];
        $response = $this->request('get');

        foreach ($response->session as $sessionInfo) {
            $sessions[(string)$sessionInfo->id] = new Struct\Info($sessionInfo);
        }

        return $sessions;
    }

    /**
     * @param string $sessionId
     *
     * @return bool
     */
    public function terminate($sessionId)
    {
        $response = $this->request("terminate.session-id=$sessionId");

        return (string)$response->status === 'ok';
    }
}
