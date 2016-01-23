<?php
/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildingProcess\BuildingProcess;
use lukaszmakuch\ObjectBuilder\BuildingProcess\Factory\BuildingProcessFactory;
use lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\Resolver\FullClassPathResolver;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\ParameterValueWithSelector;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\Selector\Matcher\ParameterMatcher;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\ValueSource\Resolver\ValueResolver;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\Selector\Matcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\ObjectBuilder;
use Object;
use ReflectionClass;
use ReflectionMethod;

class ObjectBuilderImpl implements ObjectBuilder
{
    private $buildProcessFactory;
    private $classPathResolver;
    private $methodMatcher;
    private $paramMatcher;
    private $paramValResolver;
    
    public function __construct(
        BuildingProcessFactory $processFactory,
        FullClassPathResolver $classPathResolver,
        MethodMatcher $methodMatcher,
        ParameterMatcher $paramMatcher,
        ValueResolver $paramValResolver
    ) {
        $this->buildProcessFactory = $processFactory;
        $this->classPathResolver = $classPathResolver;
        $this->methodMatcher = $methodMatcher;
        $this->paramMatcher = $paramMatcher;
        $this->paramValResolver = $paramValResolver;
    }
    
    public function finishBuildingProcess(BuildingProcess $p)
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

    public function startBuildingProcess()
    {
        return $this->buildProcessFactory->getNewBuildingProcess();
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