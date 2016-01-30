<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelector\Matcher\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\MethodSelector\Matcher\Exception\UnsupportedMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelector\Matcher\MethodMatcher;
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
