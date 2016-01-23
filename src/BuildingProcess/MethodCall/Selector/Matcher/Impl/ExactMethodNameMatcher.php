<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\Selector\Matcher\Impl;

use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\Selector\MethodSelector;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\Selector\Matcher\MethodMatcher;

class ExactMethodNameMatcher implements MethodMatcher
{
    public function methodMatches(\ReflectionMethod $method, MethodSelector $selector)
    {
        /* @var $selector \lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCal\Selector\Impl\ExactMethodName */
        return ($method->getName() === $selector->getExactMethodName());
    }
}
