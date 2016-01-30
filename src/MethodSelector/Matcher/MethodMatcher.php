<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelector\Matcher;

use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use lukaszmakuch\ObjectBuilder\MethodSelector\Matcher\Exception\UnsupportedMatcher;
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
