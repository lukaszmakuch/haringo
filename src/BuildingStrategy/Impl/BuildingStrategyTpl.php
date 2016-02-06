<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildingStrategy\Impl;

use lukaszmakuch\Haringo\BuildingStrategy\BuildingStrategy;
use lukaszmakuch\Haringo\ClassSource\FullClassPathSource;
use lukaszmakuch\Haringo\ClassSourceResolver\Exception\UnsupportedSource;
use lukaszmakuch\Haringo\ClassSourceResolver\FullClassPathResolver;
use lukaszmakuch\Haringo\Exception\BuildPlanNotFound;
use lukaszmakuch\Haringo\Exception\UnableToBuild;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\MethodSelector\MethodSelector;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Exception\UnsupportedMatcher;
use lukaszmakuch\Haringo\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;
use Object;
use ReflectionClass;
use ReflectionMethod;
use SplObjectStorage;
use UnexpectedValueException;

/**
 * Template of BuildingStrategy objects which contains common parts.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
abstract class BuildingStrategyTpl implements BuildingStrategy
{
    protected $classPathResolver;
    protected $methodMatcher;
    protected $paramListGenerator;
    protected $buildPlanByBuildObject;

    /**
     * Provides dependencies.
     * 
     * @param FullClassPathResolver $classPathResolver used to get class paths
     * @param MethodMatcher $methodMatcher used to match methods
     * against some selectors
     * @param ParameterListGenerator $paramListGenerator
     * used to generate an ordered list of method parameters
     */
    public function __construct(
        FullClassPathResolver $classPathResolver,
        MethodMatcher $methodMatcher,
        ParameterListGenerator $paramListGenerator
    ) {
        $this->classPathResolver = $classPathResolver;
        $this->methodMatcher = $methodMatcher;
        $this->paramListGenerator = $paramListGenerator;
        $this->buildPlanByBuildObject = new SplObjectStorage();
    }
    
    /**
     * @throws UnableToBuild
     */
    protected function getReflectedClassBasedOn(FullClassPathSource $classSource)
    {
        try {
            $classPath = $this->classPathResolver->resolve($classSource);
        } catch (UnsupportedSource $e) {
            throw new UnableToBuild();
        }
        
        if (!class_exists($classPath)) {
            throw new UnableToBuild();
        }
        
        return new ReflectionClass($classPath);
    }
    
    /**
     * @param Object $targetObject
     * @param \ReflectionClass $reflectedClass
     * @param MethodCall[] $allMethodCalls
     * @throws UnableToBuild when no methods found
     */
    protected function callMethodsOf(
        $targetObject,
        \ReflectionClass $reflectedClass,
        array $allMethodCalls
    ) {
        foreach ($allMethodCalls as $call) {
            $selector = $call->getSelector();
            $matchingMethods = $this->findMatchingMethods($reflectedClass, $selector);
            if (empty($matchingMethods)) {
                throw new UnableToBuild();
            }
            
            foreach ($matchingMethods as $reflectedMethod) {
                $this->callSpecifiedMethod(
                    $reflectedMethod,
                    $targetObject,
                    $call->getAssignedParamValues()
                );
            }
        }
    }
    
    /**
     * Finds all methods matching the given selector.
     * 
     * @return ReflectionMethod[]
     * @throws UnableToBuild
     */
    protected function findMatchingMethods(
        \ReflectionClass $reflectedClass,
        MethodSelector $selector
    ) {
        try {
            return $this->findMatchingMethodsImpl($reflectedClass, $selector);
        } catch (UnsupportedMatcher $e) {
            throw new UnableToBuild();
        }
    }
    
    /**
     * @return ReflectionMethod[]
     * @throws UnableToBuild when it's impossible to check methods
     */
    private function findMatchingMethodsImpl(
        \ReflectionClass $reflectedClass,
        MethodSelector $selector
    ) {
        $matchingMathods = [];
        $allMethods = $this->getReflectedMethodsAndConstructorOf($reflectedClass);
        foreach ($allMethods as $method) {
            if ($this->methodMatcher->methodMatches($method, $selector)) {
                $matchingMathods[] = $method;
            }
        }
        
        return $matchingMathods;
    }
     
    /**
     * @return \ReflectionMethod[]
     */
    protected function getReflectedMethodsAndConstructorOf(ReflectionClass $c)
    {
        return array_merge(
            $c->getMethods(\ReflectionMethod::IS_PUBLIC),
            (is_null($c->getConstructor()) ? [] : [$c->getConstructor()])
        );
    }
    
    /**
     * @param ReflectionMethod $reflectedMethod
     * @param Object|null $targetObject null if static call
     * @param AssignedParamValue[] $paramsWithSelectors
     * 
     * @return mixed method result
     * @throws UnableToBuild
     */
    protected function callSpecifiedMethod(
        ReflectionMethod $reflectedMethod, 
        $targetObject, 
        $paramsWithSelectors
    ) {
        $args = $this->paramListGenerator->getOrderedListOfArgumentValue(
            $reflectedMethod,
            $paramsWithSelectors
        );
        return $reflectedMethod->invokeArgs($targetObject, $args);
    }
}
