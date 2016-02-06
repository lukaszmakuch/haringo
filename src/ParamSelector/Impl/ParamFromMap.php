<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ParamSelector\Impl;

use lukaszmakuch\Haringo\ParamSelector\ParameterSelector;

/**
 * Provides a value stored under some key within the map.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ParamFromMap implements ParameterSelector
{
    private $key;
    
    public function __construct($keyFromMap)
    {
        $this->key = $keyFromMap;
    }
    
    public function getKeyFromMap()
    {
        return $this->key;
    }
}
