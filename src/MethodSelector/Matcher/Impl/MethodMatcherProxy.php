<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelector\Matcher\Impl;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ObjectBuilder\MethodSelector\Matcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;

class MethodMatcherProxy implements MethodMatcher
{
    private $matchersBySelectors;
    
    public function __construct()
    {
        $this->matchersBySelectors = new ClassBasedRegistry();
    }

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
