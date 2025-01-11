<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api\Struct\Server\Statistics;

class LoadAverage extends \PleskX\Api\Struct
{
    /** @var float */
    public $load1min;

    /** @var float */
    public $load5min;

    /** @var float */
    public $load15min;

    public function __construct($apiResponse)
    {
        $this->load1min = $apiResponse->l1 / 100.0;
        $this->load5min = $apiResponse->l5 / 100.0;
        $this->load15min = $apiResponse->l15 / 100.0;
    }
}
