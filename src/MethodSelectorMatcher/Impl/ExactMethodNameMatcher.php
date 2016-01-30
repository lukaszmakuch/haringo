<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\MethodMatcher;

class ExactMethodNameMatcher implements MethodMatcher
{
    public function methodMatches(\ReflectionMethod $method, MethodSelector $selector)
    {
        /* @var $selector \lukaszmakuch\ObjectBuilder\BuildPlan\MethodCal\Selector\Impl\ExactMethodName */
        return ($method->getName() === $selector->getExactMethodName());
    }
}
