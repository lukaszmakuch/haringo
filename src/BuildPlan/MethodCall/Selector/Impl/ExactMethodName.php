<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\MethodSelector;


class ExactMethodName implements MethodSelector
{
    private $methodName;
    
    public function __construct($methodName)
    {
        $this->methodName = $methodName;
    }
    
    public function getExactMethodName()
    {
        return $this->methodName;
    }
}
