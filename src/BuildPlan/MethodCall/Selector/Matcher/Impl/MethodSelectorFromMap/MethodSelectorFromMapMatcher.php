<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\Impl\MethodSelectorFromMap;

use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Resolver\FullClassPathResolver;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Impl\MethodSelectorFromMap;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\Exception\UnsupportedMatcher;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\MethodSelector;
use ReflectionMethod;

class MethodSelectorFromMapMatcher implements MethodMatcher
{
    private $map;
    private $classPathResolver;
    private $actualMatcher;
    
    public function __construct(
        MethodSelectorMap $map,
        FullClassPathResolver $classPathResolver,
        MethodMatcher $actualMatcher
    ) {
        $this->map = $map;
        $this->classPathResolver = $classPathResolver;
        $this->actualMatcher = $actualMatcher;
    }
    
    public function methodMatches(ReflectionMethod $method, MethodSelector $selector)
    {
        $actualSelector = $this->getActualMethodSelectorBy($selector, $method);
        return $this->actualMatcher->methodMatches($method, $actualSelector);
    }
    
    /**
     * @return MethodSelector
     * @throws UnsupportedMatcher
     */
    private function getActualMethodSelectorBy(MethodSelectorFromMap $selectorFromMap, ReflectionMethod $method)
    {
        $possibleIdentifiers = $this->map->getMethodIdentifiersBy($selectorFromMap);
        $methodClassPath = $method->getDeclaringClass()->getName();
        
        foreach ($possibleIdentifiers as $methodId) {
            $supportedClass = $this->classPathResolver->resolve(
                $methodId->getClassSource()
            );
            if ($supportedClass === $methodClassPath) {
                return $methodId->getMethodSelector();
            }
        }
        
        throw new UnsupportedMatcher();
    }
}
