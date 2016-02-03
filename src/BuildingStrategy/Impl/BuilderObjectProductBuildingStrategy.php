<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingStrategy\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\BuilderObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\FullClassPathResolver;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\ValueResolver;
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
     * @throws ImpossibleToFinishBuildPlan
     */
    private function denyIfIncomplete(BuilderObjectProductBuildPlan $p)
    {
        if (
            is_null($p->getBuilderSource())
            || is_null($p->getBuildMethodCall())
        ) {
            throw new ImpossibleToFinishBuildPlan();
        }
    }
    
    /**
     * @throws ImpossibleToFinishBuildPlan when the source provides something
     * else than an object.
     * @return mixed builder object
     */
    private function getBuilderBasedOn(BuilderObjectProductBuildPlan $p)
    {
        $builderSource = $p->getBuilderSource();
        $builder = $this->objectResolver->resolveValueFrom($builderSource);
        if (!is_object($builder)) {
            throw new ImpossibleToFinishBuildPlan();
        }
        
        return $builder;
    }
}
