<?php
/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Resolver\FullClassPathResolver;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\ObjectBuilder;
use Object;
use ReflectionClass;
use ReflectionMethod;

class ObjectBuilderImpl implements ObjectBuilder
{
    private $classPathResolver;
    private $methodMatcher;
    private $paramListGenerator;

    public function __construct(
        FullClassPathResolver $classPathResolver,
        MethodMatcher $methodMatcher,
        ParameterListGenerator $paramListGenerator

    ) {
        $this->classPathResolver = $classPathResolver;
        $this->methodMatcher = $methodMatcher;
        $this->paramListGenerator = $paramListGenerator;
    }
    
    public function buildObjectBasedOn(BuildPlan $p)
    {
        $reflectedClass = $this->getReflectedClassBasedOn($p->getClassSource());
        
        $builtObject = $reflectedClass->newInstanceWithoutConstructor();
        
        $this->callMethodsOf(
            $builtObject,
            $this->getReflectedMethodsAndConstructorOf($reflectedClass),
            $p->getAllMethodCalls()
        );
        
        return $builtObject;
    }
    
    private function getReflectedClassBasedOn(FullClassPathSource $classSource)
    {
        return new ReflectionClass(
            $this->classPathResolver->resolve($classSource)
        );
    }
    
    /**
     * @param Object $targetObject
     * @param ReflectionMethod[] $allReflectedMethods
     * @param MethodCall[] $allMethodCalls
     */
    private function callMethodsOf(
        $targetObject,
        array $allReflectedMethods,
        array $allMethodCalls
    ) {
        foreach ($allReflectedMethods as $reflectedMethod) {
            $this->tryToCallReflectedMethod(
                $reflectedMethod,
                $targetObject,
                $allMethodCalls
            );
        }
    }
    
    /**
     * @param ReflectionMethod $method
     * @param Object $targetObject
     * @param MethodCall[] $allMethodCalls
     */
    private function tryToCallReflectedMethod(
        ReflectionMethod $method,
        $targetObject,
        array $allMethodCalls
    ) {
        foreach ($allMethodCalls as $call) {
            if ($this->methodMatcher->methodMatches(
                $method,
                $call->getSelector()
            )) {
                $this->callSpecifiedMethod(
                    $method,
                    $targetObject,
                    $call->getParamsValueWithSelectors()
                );
            }
        }
    }
    
    /**
     * @return \ReflectionMethod[]
     */
    private function getReflectedMethodsAndConstructorOf(\ReflectionClass $c)
    {
        return array_merge(
            $c->getMethods(\ReflectionMethod::IS_PUBLIC),
            (is_null($c->getConstructor()) ? [] : [$c->getConstructor()])
        );
    }
    
    /**
     * @param ReflectionMethod $reflectedMethod
     * @param Object $targetObject
     * @param AssignedParamValue[] $paramsWithSelectors
     */
    private function callSpecifiedMethod(
        ReflectionMethod $reflectedMethod, 
        $targetObject, 
        $paramsWithSelectors
    ) {
        $reflectedMethod->invokeArgs(
            $targetObject, 
            $this->paramListGenerator->getOrderedListOfArgumentValue(
                $reflectedMethod,
                $paramsWithSelectors
            )
        );
    }
}