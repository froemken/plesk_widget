<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Plesk;

class Site extends \PleskX\Api\Struct
{
    public string $filterId;

    public string $id;

    public string $status;

    /** @var \StefanFroemken\PleskWidget\Plesk\Site\GeneralInfo */
    public $genInfo;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'filter-id',
            'id',
            'status',
        ]);
    }
}
