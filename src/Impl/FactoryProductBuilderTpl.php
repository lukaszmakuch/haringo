<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use ReflectionClass;

abstract class FactoryProductBuilderTpl extends BuilderTpl
{
    protected function findFactoryMethod(
        ReflectionClass $reflectedFactoryClass,    
        MethodSelector $factoryMethodSelector
    ) {
        $allMatchingMethods = $this->findMatchingMethods(
            $reflectedFactoryClass,
            $factoryMethodSelector
        );
        $this->throwExceptionIfWrongNumberOfFactoryMethods($allMatchingMethods);
        return $allMatchingMethods[0];
    }
    
    /**
     * @param array $reflectedFactoryMethods
     * @throws ImpossibleToFinishBuildPlan when there is less or more methods than 1
     */
    protected function throwExceptionIfWrongNumberOfFactoryMethods(array $reflectedFactoryMethods)
    {
        if (count($reflectedFactoryMethods) != 1) {
            throw new ImpossibleToFinishBuildPlan(
                "factory method selector matches different than 1 number of methods"
            );
        }
    }
}
