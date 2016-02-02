<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelectorMatcher;

use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Exception\UnsupportedMatcher;
use ReflectionParameter;

/**
 * Checks whether some parameter matches some selector.
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ParameterMatcher
{
    /**
     * Checks whether the parameter matches.
     * 
     * @return bool true if the given param matches this selector
     * @throws UnsupportedMatcher
     */
    public function parameterMatches(
        ReflectionParameter $param,
        ParameterSelector $selector
    );
}
