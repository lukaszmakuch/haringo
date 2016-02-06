<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher;

use lukaszmakuch\Haringo\ClassSourceResolver\FullClassPathResolver;
use lukaszmakuch\Haringo\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\Haringo\ParamSelector\ParameterSelector;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Exception\UnsupportedMatcher;
use lukaszmakuch\Haringo\ParamSelectorMatcher\ParameterMatcher;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Allows to check whether some parameter from the map matches some reflected
 * parameter.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ParamFromMapMatcher implements ParameterMatcher
{
    private $map;
    private $classPathResolver;
    private $methodMatcher;
    private $actualParamMatcher;
    
    /**
     * Provides dependencies.
     * 
     * @param ParamSelectorMap $map map of parameters
     * @param FullClassPathResolver $classPathResolver source of full class paths
     * @param MethodMatcher $methodMatcher service that allows to match methods
     * against some selectors
     * @param ParameterMatcher $actualParamMatcher checks whether the 
     * selector found within the map grabs some param.
     */
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
