<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelector\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;

class MethodSelectorFromMap implements MethodSelector
{
    private $keyFromMap;
    
    /**
     * @param String $keyFromMap
     */
    public function __construct($keyFromMap)
    {
        $this->keyFromMap = $keyFromMap;
    }

    /**
     * @return string
     */
    public function getKeyFromMap()
    {
        return $this->keyFromMap;
    }
}
