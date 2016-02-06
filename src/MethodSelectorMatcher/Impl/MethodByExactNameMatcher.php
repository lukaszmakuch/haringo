<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\MethodSelectorMatcher\Impl;

use lukaszmakuch\Haringo\MethodSelector\MethodSelector;
use lukaszmakuch\Haringo\MethodSelectorMatcher\MethodMatcher;

/**
 * Checks whether some method is called exactly as the selector says it should
 * be called.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MethodByExactNameMatcher implements MethodMatcher
{
    public function methodMatches(\ReflectionMethod $method, MethodSelector $selector)
    {
        /* @var $selector \lukaszmakuch\Haringo\BuildPlan\MethodCal\Selector\Impl\MethodByExactName */
        return ($method->getName() === $selector->getMethodByExactName());
    }
}
