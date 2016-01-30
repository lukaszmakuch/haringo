<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\ParameterMatcher;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use ReflectionParameter;

class ParameterMatcherProxy implements ParameterMatcher
{
    private $matchersBySelectors;
    
    public function __construct()
    {
        $this->matchersBySelectors = new ClassBasedRegistry();
    }

    public function registerMatcher(ParameterMatcher $m, $targetParamSelectorClass)
    {
        $this->matchersBySelectors->associateValueWithClasses(
            $m,
            [$targetParamSelectorClass]
        );
    }
    
    public function parameterMatches(
        ReflectionParameter $param,
        ParameterSelector $selector
    ) {
        $actualMatcher = $this->getMatcherBy($selector);
        return $actualMatcher->parameterMatches($param, $selector);
    }

    /**
     * 
     * @param ParameterSelector $selector
     * @return ParameterMatcher
     */
    private function getMatcherBy(ParameterSelector $selector)
    {
        return $this->matchersBySelectors->fetchValueByObjects([$selector]);
    }
}
