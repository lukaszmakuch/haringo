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

/**
 * Allows to assign different matchers to different classes of selectors.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ParameterMatcherProxy implements ParameterMatcher
{
    private $matchersBySelectors;
    
    public function __construct()
    {
        $this->matchersBySelectors = new ClassBasedRegistry();
    }

    /**
     * Assigns some matcher to some type of selectors.
     * 
     * @param ParameterMatcher $m matcher to use
     * @param String $targetParamSelectorClass full class path of parameter
     * selector that should be checked by the given matcher
     */
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
     * Gets a matcher suitable for the given selector.
     * 
     * @param ParameterSelector $selector
     * @return ParameterMatcher
     */
    private function getMatcherBy(ParameterSelector $selector)
    {
        return $this->matchersBySelectors->fetchValueByObjects([$selector]);
    }
}
