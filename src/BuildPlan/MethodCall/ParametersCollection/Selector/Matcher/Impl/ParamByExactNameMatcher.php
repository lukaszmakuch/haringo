<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Matcher\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Matcher\ParameterMatcher;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ParameterSelector;
use ReflectionParameter;

/**
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
