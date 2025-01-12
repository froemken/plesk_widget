<?php

declare(strict_types=1);

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace StefanFroemken\PleskWidget\Builder;

/*
 * Builder to create ExtConf object
 */
readonly class ExtConfBuilderFactory
{
    public function createBuilder(): ExtConfBuilder
    {
        return new ExtConfBuilder();
    }
}
