<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ParameterSelector;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ParamByExactName implements ParameterSelector
{
    private $exactParameterName;
    
    /**
     * @param String $exactParameterName
     */
    public function __construct($exactParameterName)
    {
        $this->exactParameterName = $exactParameterName;
    }
    
    /**
     * @return String
     */
    public function getExactName()
    {
        return $this->exactParameterName;
    }
}
