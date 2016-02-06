<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap;

use lukaszmakuch\Haringo\ClassSourceResolver\FullClassPathResolver;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodFromMap;
use lukaszmakuch\Haringo\MethodSelector\MethodSelector;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Exception\UnsupportedMatcher;
use lukaszmakuch\Haringo\MethodSelectorMatcher\MethodMatcher;
use ReflectionMethod;

/**
 * Maps a selector from the map.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MethodFromMapMatcher implements MethodMatcher
{
    private $map;
    private $classPathResolver;
    private $actualMatcher;
   
    /**
     * Provides dependencies.
     * 
     * @param MethodSelectorMap $map map of selectors under some keys
     * @param FullClassPathResolver $classPathResolver resolver of full class paths
     * @param MethodMatcher $actualMatcher matcher of method calls.
     */
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
    private function getActualMethodSelectorBy(MethodFromMap $selectorFromMap, ReflectionMethod $method)
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
