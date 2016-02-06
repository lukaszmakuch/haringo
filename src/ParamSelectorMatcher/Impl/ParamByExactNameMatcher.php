<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ParamSelectorMatcher\Impl;

use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamSelectorMatcher\ParameterMatcher;
use lukaszmakuch\Haringo\ParamSelector\ParameterSelector;
use ReflectionParameter;

/**
 * Checks whether some reflected param matches the given
 * param by exact name selector.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ParamByExactNameMatcher implements ParameterMatcher
{
    public function parameterMatches(
        ReflectionParameter $param,
        ParameterSelector $selector
    ) {
        /* @var $selector ParamByExactName */
        return ($selector->getExactName() == $param->getName());
    }
}
