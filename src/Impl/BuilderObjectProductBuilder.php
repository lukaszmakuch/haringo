<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\FactoryObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\FullClassPathResolver;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\ValueResolver;

class BuilderObjectProductBuilder extends FactoryProductBuilderTpl
{
    protected $objectResolver;
    
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
        /* @var $p \lukaszmakuch\ObjectBuilder\BuildPlan\Impl\BuilderObjectProductBuildPlan */
        $builderObject = $this->objectResolver->resolveValueFrom($p->getBuilderSource());
        $reflectedBuilder = new \ReflectionObject($builderObject);
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
}
