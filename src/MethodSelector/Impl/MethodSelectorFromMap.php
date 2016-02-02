<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelector\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;

/**
 * Holds key from the method map.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MethodSelectorFromMap implements MethodSelector
{
    private $keyFromMap;
    
    /**
     * Sets the key from the method map.
     * 
     * @param String $keyFromMap
     */
    public function __construct($keyFromMap)
    {
        $this->keyFromMap = $keyFromMap;
    }

    /**
     * @return string a key from the map
     */
    public function getKeyFromMap()
    {
        return $this->keyFromMap;
    }
}
