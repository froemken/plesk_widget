<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Server\Statistics;

class Version extends \PleskX\Api\Struct
{
    /** @var string */
    public $internalName;

    /** @var string */
    public $version;

    /** @var string */
    public $build;

    /** @var string */
    public $osName;

    /** @var string */
    public $osVersion;

    /** @var string */
    public $osRelease;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            ['plesk_name' => 'internalName'],
            ['plesk_version' => 'version'],
            ['plesk_build' => 'build'],
            ['plesk_os' => 'osName'],
            ['plesk_os_version' => 'osVersion'],
            ['os_release' => 'osRelease'],
        ]);
    }
}
