<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\MethodSelectorMatcher\Impl;

use lukaszmakuch\Haringo\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Exception\UnsupportedMatcher;
use lukaszmakuch\Haringo\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\Haringo\MethodSelector\MethodSelector;
use ReflectionMethod;

/**
 * Matches a method against the constructor selector.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ConstructorSelectorMatcher implements MethodMatcher
{
    public function methodMatches(ReflectionMethod $method, MethodSelector $selector)
    {
        if (false === ($selector instanceof ConstructorSelector)) {
            throw new UnsupportedMatcher();
        }
        
        return $method->isConstructor();
    }
}
