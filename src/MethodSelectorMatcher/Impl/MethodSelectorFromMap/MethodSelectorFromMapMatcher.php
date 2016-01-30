<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodSelectorFromMap;

use lukaszmakuch\ObjectBuilder\ClassSourceResolver\FullClassPathResolver;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\MethodSelectorFromMap;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Exception\UnsupportedMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
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
