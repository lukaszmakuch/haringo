<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamFromMapMatcher;

use lukaszmakuch\ObjectBuilder\ClassSourceResolver\FullClassPathResolver;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Exception\UnsupportedMatcher;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\ParameterMatcher;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\MethodMatcher;
use ReflectionMethod;
use ReflectionParameter;

class ParamFromMapMatcher implements ParameterMatcher
{
    private $map;
    private $classPathResolver;
    private $methodMatcher;
    private $actualParamMatcher;
    
    public function __construct(
        ParamSelectorMap $map,
        FullClassPathResolver $classPathResolver,
        MethodMatcher $methodMatcher,
        ParameterMatcher $actualParamMatcher
    ) {
        $this->map = $map;
        $this->classPathResolver = $classPathResolver;
        $this->methodMatcher = $methodMatcher;
        $this->actualParamMatcher = $actualParamMatcher;
    }
    
    public function parameterMatches(
        ReflectionParameter $param,
        ParameterSelector $selector
    ) {
        /* @var $method ReflectionMethod */
        $method = $param->getDeclaringFunction();
        $className = $method->getDeclaringClass()->getName();
        
        // possible identifiers
        $possibleIdentifiers = $this->map->getParamSelectorBy($selector);
        foreach ($possibleIdentifiers as $singleIdentifier) {
            $paramSelector = $singleIdentifier->getActualParamSelector();
            $methodIdentifier = $singleIdentifier->getFullMethodIdentifier();
            $classSource = $methodIdentifier->getClassSource();
            $methodSelector = $methodIdentifier->getMethodSelector();
            $desiredClass = $this->classPathResolver->resolve($classSource);
            if (
                ($className === $desiredClass)
                && $this->methodMatcher->methodMatches($method, $methodSelector)
            ) {
                return $this->actualParamMatcher->parameterMatches($param, $paramSelector);
            }
        }
    
        throw new UnsupportedMatcher();
    }
}