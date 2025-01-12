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
 * This class streamlines all settings from extension settings
 */
readonly class ExtConf
{
    public function __construct(
        private CredentialsConfiguration $credentialsConfiguration,
        private ViewConfiguration $viewConfiguration
    ) {}

    public function getCredentialsConfiguration(): CredentialsConfiguration
    {
        return $this->credentialsConfiguration;
    }

    public function getViewConfiguration(): ViewConfiguration
    {
        return $this->viewConfiguration;
    }
}
