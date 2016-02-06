<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildingStrategy\Impl;

use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\BuilderObjectProductBuildPlan;
use lukaszmakuch\Haringo\ClassSourceResolver\FullClassPathResolver;
use lukaszmakuch\Haringo\Exception\UnableToBuild;
use lukaszmakuch\Haringo\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\Haringo\ValueSourceResolver\ValueResolver;
use ReflectionObject;

/**
 * Builds objects based on build plans which describe how to use some builder
 * object to get the product.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuilderObjectProductBuildingStrategy extends FactoryProductBuildingStrategyTpl
{
    protected $objectResolver;
    
    /**
     * Provides dependencies.
     * 
     * @param FullClassPathResolver $classPathResolver allows to get class paths
     * @param MethodMatcher $methodMatcher allows to match methods 
     * against some selectors
     * @param ParameterListGenerator $paramListGenerator generates ordered
     * list of method parameters
     * @param ValueResolver $objectResolver gets values based on their sources
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
        /* @var $p BuilderObjectProductBuildPlan */
        $this->denyIfIncomplete($p);
        $builderObject = $this->getBuilderBasedOn($p);
        $reflectedBuilder = new ReflectionObject($builderObject);
        $this->callMethodsOf(
            $builderObject,
            $reflectedBuilder,
            $p->getAllSettingMethodCalls()
        );
        
        $buildMethod = $this->findFactoryMethod(
            $reflectedBuilder,
            $p->getBuildMethodCall()->getSelector()
        );
        $builtObject = $this->callSpecifiedMethod(
            $buildMethod,
            $builderObject, 
            $p->getBuildMethodCall()->getAssignedParamValues()
        );

        $this->buildPlanByBuildObject->attach($builtObject, $p);
        return $builtObject;
    }
    
    /**
     * @throws UnableToBuild
     */
    private function denyIfIncomplete(BuilderObjectProductBuildPlan $p)
    {
        if (
            is_null($p->getBuilderSource())
            || is_null($p->getBuildMethodCall())
        ) {
            throw new UnableToBuild();
        }
    }
    
    /**
     * @throws UnableToBuild when the source provides something
     * else than an object.
     * @return mixed builder object
     */
    private function getBuilderBasedOn(BuilderObjectProductBuildPlan $p)
    {
        $builderSource = $p->getBuilderSource();
        $builder = $this->objectResolver->resolveValueFrom($builderSource);
        if (!is_object($builder)) {
            throw new UnableToBuild();
        }
        
        return $builder;
    }
}
