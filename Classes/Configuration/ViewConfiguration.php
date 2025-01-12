<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Configuration;

/*
 * This class streamlines view related settings from extension settings
 */
readonly class ViewConfiguration
{
    public function __construct(
        private DiskUsageTypeEnum $diskUsageType,
        private string $domain
    ) {}

    public function getDiskUsageType(): string
    {
        return $this->diskUsageType->value;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }
}
