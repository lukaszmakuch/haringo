<?php
/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Factory\BuildPlanFactory;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Resolver\FullClassPathResolver;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ParameterValueWithSelector;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Matcher\ParameterMatcher;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Resolver\ValueResolver;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\ObjectBuilder;
use Object;
use ReflectionClass;
use ReflectionMethod;

class ObjectBuilderImpl implements ObjectBuilder
{
    private $classPathResolver;
    private $methodMatcher;
    private $paramMatcher;
    private $paramValResolver;
    
    public function __construct(
        FullClassPathResolver $classPathResolver,
        MethodMatcher $methodMatcher,
        ParameterMatcher $paramMatcher,
        ValueResolver $paramValResolver
    ) {
        $this->classPathResolver = $classPathResolver;
        $this->methodMatcher = $methodMatcher;
        $this->paramMatcher = $paramMatcher;
        $this->paramValResolver = $paramValResolver;
    }
    
    public function buildObjectBasedOn(BuildPlan $p)
    {
        $reflectedClass = $this->getReflectedClassBasedOn($p->getClassSource());
        
        $builtObject = $reflectedClass->newInstanceWithoutConstructor();
        
        $this->callMethods(
            $builtObject,
            $this->getReflectedMethodsAndConstructor($reflectedClass),
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
     * @return \ReflectionMethod[]
     */
    private function getReflectedMethodsAndConstructor(\ReflectionClass $c)
    {
        return array_merge(
            $c->getMethods(\ReflectionMethod::IS_PUBLIC),
            (is_null($c->getConstructor()) ? [] : [$c->getConstructor()])
        );
    }
    
    /**
     * @param Object $targetObject
     * @param ReflectionMethod[] $allReflectedMethods
     * @param MethodCall[] $allMethodCalls
     */
    private function callMethods($targetObject, array $allReflectedMethods, array $allMethodCalls)
    {
        foreach ($allReflectedMethods as $reflectedMethod) {
            foreach ($allMethodCalls as $methodCall) {
                if ($this->methodMatcher->methodMatches($reflectedMethod, $methodCall->getSelector())) {
                    $this->callSpecifiedMethod($reflectedMethod, $targetObject, $methodCall->getParamsValueWithSelectors());
                }
            }
        }
    }
    
    /**
     * @param ReflectionMethod $reflectedMethod
     * @param Object $targetObject
     * @param ParameterValueWithSelector[] $paramsWithSelectors
     */
    private function callSpecifiedMethod(
        ReflectionMethod $reflectedMethod, 
        $targetObject, 
        $paramsWithSelectors
    ) {
        $reflectedMethod->invokeArgs(
            $targetObject, 
            $this->getListOfArguments($reflectedMethod, $paramsWithSelectors)
        );
    }
    

    
    /**
     * @param ReflectionMethod $reflectedMethod
     * @param ParameterValueWithSelector[] $paramsWithSelectors
     */
    private function getListOfArguments(
        ReflectionMethod $reflectedMethod, 
        $paramsWithSelectors
    ) {
        $listOfValues = [];
        $allReflectedParams = $reflectedMethod->getParameters();
        $numberOfReflectedParms = count($allReflectedParams);
        for ($paramIndex = 0; $paramIndex < $numberOfReflectedParms; $paramIndex++) {
            $listOfValues[$paramIndex] = null;
            foreach ($paramsWithSelectors as $singleParamWithSelector) {
                if ($this->paramMatcher->parameterMatches($allReflectedParams[$paramIndex], $singleParamWithSelector->getSelector())) {
                    $listOfValues[$paramIndex] = $this->paramValResolver->resolveValueFrom($singleParamWithSelector->getValueSource());
                }
            }
        }
        
        return $listOfValues;
    }
    
}