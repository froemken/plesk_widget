<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api;

/**
 * Internal client for Plesk XML-RPC API (via SDK).
 */
class InternalClient extends Client
{
    public function __construct()
    {
        parent::__construct('localhost', 0, 'sdk');
    }

    /**
     * Setup login to execute requests under certain user.
     */
    public function setLogin($login)
    {
        $this->_login = $login;
    }
}
