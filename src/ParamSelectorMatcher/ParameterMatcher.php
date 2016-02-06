<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ParamSelectorMatcher;

use lukaszmakuch\Haringo\ParamSelector\ParameterSelector;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Exception\UnsupportedMatcher;
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
