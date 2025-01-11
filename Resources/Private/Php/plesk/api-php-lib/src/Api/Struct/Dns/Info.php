<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Dns;

class Info extends \PleskX\Api\Struct
{
    /** @var int */
    public $id;

    /** @var int */
    public $siteId;

    /** @var int */
    public $siteAliasId;

    /** @var string */
    public $type;

    /** @var string */
    public $host;

    /** @var string */
    public $value;

    /** @var string */
    public $opt;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'site-id',
            'site-alias-id',
            'type',
            'host',
            'value',
            'opt',
        ]);
    }
}
