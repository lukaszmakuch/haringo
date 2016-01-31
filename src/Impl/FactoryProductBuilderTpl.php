<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\ClassSourceResolver\FullClassPathResolver;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\ObjectResolver;
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
    
    protected function throwExceptionIfWrongNumberOfFactoryMethods(array $reflectedFactoryMethods)
    {
        if (count($reflectedFactoryMethods) != 1) {
            throw new ImpossibleToFinishBuildPlan(
                "factory method selector matches different than 1 number of methods"
            );
        }
    }
}
