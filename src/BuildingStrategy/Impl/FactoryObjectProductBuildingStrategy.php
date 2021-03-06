<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildingStrategy\Impl;

use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\FactoryObjectProductBuildPlan;
use lukaszmakuch\Haringo\ClassSourceResolver\FullClassPathResolver;
use lukaszmakuch\Haringo\Exception\UnableToBuild;
use lukaszmakuch\Haringo\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\Haringo\ValueSourceResolver\ValueResolver;
use ReflectionObject;

/**
 * Builds objects based on a description how should they be build using a factory
 * object.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FactoryObjectProductBuildingStrategy extends FactoryProductBuildingStrategyTpl
{
    protected $objectResolver;
    
    /**
     * Provieds dependencies.
     * 
     * @param FullClassPathResolver $classPathResolver used to get class paths
     * @param MethodMatcher $methodMatcher used to match methods
     * against some selectors
     * @param ParameterListGenerator $paramListGenerator
     * used to generate an ordered list of method parameters
     * @param ValueResolver $objectResolver provides factory object
     */
    public function __construct(
        FullClassPathResolver $classPathResolver,
        MethodMatcher $methodMatcher,
        ParameterListGenerator $paramListGenerator,
        ValueResolver $objectResolver
    ) {
        parent::__construct($classPathResolver, $methodMatcher, $paramListGenerator);
        $this->objectResolver = $objectResolver;
    }
    
    public function buildObjectBasedOn(BuildPlan $p)
    {
        $this->denyUnlessComplete($p);
        /* @var $p FactoryObjectProductBuildPlan */
        $factoryObject = $this->getFactoryObjectBasedOn($p);
        
        $factoryMethod = $this->findFactoryMethod(
            new ReflectionObject($factoryObject),
            $p->getBuildMethodCall()->getSelector()
        );

        $builtObject = $this->callSpecifiedMethod(
            $factoryMethod,
            $factoryObject,
            $p->getBuildMethodCall()->getAssignedParamValues()
        );

        $this->buildPlanByBuildObject->attach($builtObject, $p);
        return $builtObject;
    }
    
    /**
     * @param FactoryObjectProductBuildPlan $p
     * @throws UnableToBuild when the plan is not completed
     */
    private function denyUnlessComplete(FactoryObjectProductBuildPlan $p)
    {
        if (
            is_null($p->getBuildMethodCall())
            || is_null($p->getFactoryObjectSource())
        ) {
            throw new UnableToBuild();
        }
    }
    
    /**
     * @return mixed factory object
     * @throws UnableToBuild when got something different than an object
     */
    private function getFactoryObjectBasedOn(FactoryObjectProductBuildPlan $p)
    {
        $factorySource = $p->getFactoryObjectSource();
        $factory = $this->objectResolver->resolveValueFrom($factorySource);
        if (!is_object($factory)) {
            throw new UnableToBuild();
        }
        
        return $factory;
    }
}
