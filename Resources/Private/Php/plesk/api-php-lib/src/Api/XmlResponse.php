<?php

/*
 * This file is part of the package stefanfroemken/plesk-widget.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace PleskX\Api;

/**
 * XML wrapper for responses.
 */
class XmlResponse extends \SimpleXMLElement
{
    /**
     * Retrieve value by node name.
     *
     * @param string $node
     *
     * @return string
     */
    public function getValue($node)
    {
        return (string)$this->xpath('//' . $node)[0];
    }
}
