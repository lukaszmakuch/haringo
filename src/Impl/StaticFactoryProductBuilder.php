<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\StaticFactoryProductBuildPlan;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;

class StaticFactoryProductBuilder extends BuilderTpl
{
    public function buildObjectBasedOn(BuildPlan $p)
    {
        /* @var $p StaticFactoryProductBuildPlan */
        $reflectedFactoryClass = $this->getReflectedClassBasedOn($p->getFactoryClass());
        
        $allMatchingMethods = $this->findMatchingMethods(
            $reflectedFactoryClass,
            $p->getFactoryMethodCall()->getSelector()
        );
        
        if (count($allMatchingMethods) != 1) {
            throw new ImpossibleToFinishBuildPlan(
                "factory method selector matches different than 1 number of methods"
            );
        }
        
        $factoryMethod = $allMatchingMethods[0];
        
        $builtObject = $this->callSpecifiedMethod(
            $factoryMethod,
            null, //static
            $p->getFactoryMethodCall()->getAssignedParamValues()
        );
        
        $this->buildPlanByBuildObject->attach($builtObject, $p);
        return $builtObject;
    }
}
