<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildingStrategy\Impl;

use lukaszmakuch\Haringo\Exception\UnableToBuild;
use lukaszmakuch\Haringo\MethodSelector\MethodSelector;
use ReflectionClass;

/**
 * Contains common parts of factory product building strategies. 
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class FactoryProductBuildingStrategyTpl extends BuildingStrategyTpl
{
    /**
     * Gets the factory method. The selector must pick exactly 1 object.
     * 
     * @param ReflectionClass $reflectedFactoryClass
     * @param MethodSelector $factoryMethodSelector
     * @return ReflectionMethod
     */
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
     * @throws UnableToBuild when there is less or more methods than 1
     */
    protected function throwExceptionIfWrongNumberOfFactoryMethods(array $reflectedFactoryMethods)
    {
        if (count($reflectedFactoryMethods) != 1) {
            throw new UnableToBuild(
                "factory method selector matches different than 1 number of methods"
            );
        }
    }
}
