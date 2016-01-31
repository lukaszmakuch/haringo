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
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use ReflectionClass;

class StaticFactoryProductBuilder extends BuilderTpl
{
    public function buildObjectBasedOn(BuildPlan $p)
    {
        /* @var $p StaticFactoryProductBuildPlan */
        $factoryMethod = $this->findFactoryMethod(
            $this->getReflectedClassBasedOn($p->getFactoryClass()),
            $p->getFactoryMethodCall()->getSelector()
        );

        $builtObject = $this->callSpecifiedMethod(
            $factoryMethod,
            null, //static
            $p->getFactoryMethodCall()->getAssignedParamValues()
        );

        $this->buildPlanByBuildObject->attach($builtObject, $p);
        return $builtObject;
    }
    
    private function findFactoryMethod(
        ReflectionClass $reflectedFactoryClass,    
        MethodSelector $factoryMethodSelector
    ) {
        $allMatchingMethods = $this->findMatchingMethods(
            $reflectedFactoryClass,
            $factoryMethodSelector
        );
        $this->throwExceptionIfWrongNumberOfMethods($allMatchingMethods);
        return $allMatchingMethods[0];
    }
    
    private function throwExceptionIfWrongNumberOfMethods(array $reflectedFactoryMethods)
    {
        if (count($reflectedFactoryMethods) != 1) {
            throw new ImpossibleToFinishBuildPlan(
                "factory method selector matches different than 1 number of methods"
            );
        }
    }
}
