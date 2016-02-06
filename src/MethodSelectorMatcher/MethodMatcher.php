<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\MethodSelectorMatcher;

use lukaszmakuch\Haringo\MethodSelector\MethodSelector;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Exception\UnsupportedMatcher;
use ReflectionMethod;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface MethodMatcher
{
    /**
     * @return boolean true if the given method matches this selector
     * @throws UnsupportedMatcher
     */
    public function methodMatches(
        ReflectionMethod $method,
        MethodSelector $selector
    );
}
