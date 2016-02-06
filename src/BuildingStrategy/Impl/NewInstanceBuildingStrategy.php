<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildingStrategy\Impl;

use lukaszmakuch\Haringo\BuildPlan\BuildPlan;

/**
 * Builds a new object based on it's class and method called on it.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class NewInstanceBuildingStrategy extends BuildingStrategyTpl
{
    public function buildObjectBasedOn(BuildPlan $p)
    {
        $reflectedClass = $this->getReflectedClassBasedOn($p->getClassSource());
        
        $builtObject = $reflectedClass->newInstanceWithoutConstructor();
        
        $this->callMethodsOf(
            $builtObject,
            $reflectedClass,
            $p->getAllMethodCalls()
        );
        
        $this->buildPlanByBuildObject->attach($builtObject, $p);
        return $builtObject;
    }
}
