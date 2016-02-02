<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl;

use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\ParameterMatcher;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use ReflectionParameter;

/**
 * Checks whether some reflected param matches
 * the given param byby position selector.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ParamByPositionMatcher implements ParameterMatcher
{
    public function parameterMatches(ReflectionParameter $param, ParameterSelector $selector)
    {
        /* @var $selector ParamByPosition */
        return ($param->getPosition() === $selector->getParamPosFrom0());
    }
}
