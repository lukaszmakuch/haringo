<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ParamSelectorMatcher\Impl;

use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\Haringo\ParamSelectorMatcher\ParameterMatcher;
use lukaszmakuch\Haringo\ParamSelector\ParameterSelector;
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
