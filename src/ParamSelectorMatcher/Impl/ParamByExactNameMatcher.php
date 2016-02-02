<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl;

use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\ParameterMatcher;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
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
