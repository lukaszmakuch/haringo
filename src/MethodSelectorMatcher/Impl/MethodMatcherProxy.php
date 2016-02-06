<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\MethodSelectorMatcher\Impl;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\Haringo\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\Haringo\MethodSelector\MethodSelector;

/**
 * Proxy of method matchers.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MethodMatcherProxy implements MethodMatcher
{
    private $matchersBySelectors;
    
    public function __construct()
    {
        $this->matchersBySelectors = new ClassBasedRegistry();
    }

    /**
     * Assigns actual matcher to some class of method matchers.
     * @param MethodMatcher $m
     * @param String $targetMethodSelectorClass
     */
    public function registerMatcher(MethodMatcher $m, $targetMethodSelectorClass)
    {
        $this->matchersBySelectors->associateValueWithClasses(
            $m,
            [$targetMethodSelectorClass]
        );
    }
    
    public function methodMatches(
        \ReflectionMethod $method,
        MethodSelector $selector
    ) {
        $actualMatcher = $this->getMatcherBy($selector);
        return $actualMatcher->methodMatches($method, $selector);
    }
    
    /**
     * 
     * @param MethodSelector $selector
     * @return MethodMatcher
     */
    private function getMatcherBy(MethodSelector $selector)
    {
        return $this->matchersBySelectors->fetchValueByObjects([$selector]);
    }
}
