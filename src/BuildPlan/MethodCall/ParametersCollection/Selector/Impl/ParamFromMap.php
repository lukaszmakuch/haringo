<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ParameterSelector;

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
