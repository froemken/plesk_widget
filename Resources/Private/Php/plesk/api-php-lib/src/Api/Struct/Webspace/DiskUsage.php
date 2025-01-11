<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

// Author: Frederic Leclercq

namespace PleskX\Api\Struct\Webspace;

class DiskUsage extends \PleskX\Api\Struct
{
    /** @var int */
    public $httpdocs;

    /** @var int */
    public $httpsdocs;

    /** @var int */
    public $subdomains;

    /** @var int */
    public $anonftp;

    /** @var int */
    public $logs;

    /** @var int */
    public $dbases;

    /** @var int */
    public $mailboxes;

    /** @var int */
    public $maillists;

    /** @var int */
    public $domaindumps;

    /** @var int */
    public $configs;

    /** @var int */
    public $chroot;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'httpdocs',
            'httpsdocs',
            'subdomains',
            'anonftp',
            'logs',
            'dbases',
            'mailboxes',
            'maillists',
            'domaindumps',
            'configs',
            'chroot',
        ]);
    }
}
