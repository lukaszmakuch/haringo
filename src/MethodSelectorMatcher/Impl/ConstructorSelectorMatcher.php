<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Exception\UnsupportedMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use ReflectionMethod;

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
