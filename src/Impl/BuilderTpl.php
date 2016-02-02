<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\FullClassPathResolver;
use lukaszmakuch\ObjectBuilder\Exception\BuildPlanNotFound;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Exception\UnsupportedMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\ObjectBuilder;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use Object;
use ReflectionClass;
use ReflectionMethod;
use SplObjectStorage;
use UnexpectedValueException;

abstract class BuilderTpl implements ObjectBuilder
{
    protected $classPathResolver;
    protected $methodMatcher;
    protected $paramListGenerator;
    protected $buildPlanByBuildObject;

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
    

    public function getBuildPlanUsedToBuild($previouslyBuiltObject)
    {
        try {
            return $this->buildPlanByBuildObject->offsetGet($previouslyBuiltObject);
        } catch (UnexpectedValueException $e) {
            throw new BuildPlanNotFound("this builder has not been used to build the given object");
        }
    }
    
    protected function getReflectedClassBasedOn(FullClassPathSource $classSource)
    {
        return new ReflectionClass(
            $this->classPathResolver->resolve($classSource)
        );
    }
    
    /**
     * @param Object $targetObject
     * @param \ReflectionClass $reflectedClass
     * @param MethodCall[] $allMethodCalls
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
                throw new ImpossibleToFinishBuildPlan();
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
     */
    protected function findMatchingMethods(
        \ReflectionClass $reflectedClass,
        MethodSelector $selector
    ) {
        $matchingMathods = [];
        $allMethods = $this->getReflectedMethodsAndConstructorOf($reflectedClass);
        foreach ($allMethods as $method) {
            try {
                if ($this->methodMatcher->methodMatches($method, $selector)) {
                    $matchingMathods[] = $method;
                }
            } catch (UnsupportedMatcher $e) {
                throw new ImpossibleToFinishBuildPlan();
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
     */
    protected function callSpecifiedMethod(
        ReflectionMethod $reflectedMethod, 
        $targetObject, 
        $paramsWithSelectors
    ) {
        return $reflectedMethod->invokeArgs(
            $targetObject, 
            $this->paramListGenerator->getOrderedListOfArgumentValue(
                $reflectedMethod,
                $paramsWithSelectors
            )
        );
    }
}
