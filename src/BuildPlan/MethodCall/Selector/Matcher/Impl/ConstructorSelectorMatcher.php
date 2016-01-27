<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\Exception\UnsupportedMatcher;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\MethodSelector;
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
