<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\MethodSelector\Impl;

use lukaszmakuch\Haringo\MethodSelector\MethodSelector;

/**
 * Holds key from the method map.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MethodFromMap implements MethodSelector
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
