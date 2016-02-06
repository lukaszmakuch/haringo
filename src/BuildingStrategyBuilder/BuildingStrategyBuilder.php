<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildingStrategyBuilder;

use lukaszmakuch\Haringo\BuildingStrategy\BuildingStrategy;
use lukaszmakuch\Haringo\BuildingStrategy\Impl\BuilderObjectProductBuildingStrategy;
use lukaszmakuch\Haringo\BuildingStrategy\Impl\BuildingStrategyProxy;
use lukaszmakuch\Haringo\BuildingStrategy\Impl\FactoryObjectProductBuildingStrategy;
use lukaszmakuch\Haringo\BuildingStrategy\Impl\NewInstanceBuildingStrategy;
use lukaszmakuch\Haringo\BuildingStrategy\Impl\ParameterListGenerator;
use lukaszmakuch\Haringo\BuildingStrategy\Impl\StaticFactoryProductBuildingStrategy;
use lukaszmakuch\Haringo\BuildingStrategyBuilder\Extension\BuildingStrategyValueSourceExtension;
use lukaszmakuch\Haringo\BuildPlan\Impl\BuilderObjectProductBuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\FactoryObjectProductBuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\StaticFactoryProductBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ClassPathFromMap;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathFromMapResolver;
use lukaszmakuch\Haringo\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\Haringo\ClassSourceResolver\Impl\ClassPathResolverProxy;
use lukaszmakuch\Haringo\ClassSourceResolver\Impl\ExactClassPathResolver;
use lukaszmakuch\Haringo\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodFromMap;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\ConstructorSelectorMatcher;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodByExactNameMatcher;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodMatcherProxy;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\MethodFromMapMatcher;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\MethodSelectorMap;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamFromMap;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamByExactNameMatcher;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamByPositionMatcher;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParameterMatcherProxy;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamFromMapMatcher;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;
use lukaszmakuch\Haringo\ValueSource\Impl\ArrayValue;
use lukaszmakuch\Haringo\ValueSource\Impl\BuildPlanResultValue;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;
use lukaszmakuch\Haringo\ValueSourceResolver\Impl\ArrayValue\ArrayValueResolver;
use lukaszmakuch\Haringo\ValueSourceResolver\Impl\BuildPlanResultValue\BuildPlanResultValueResolver;
use lukaszmakuch\Haringo\ValueSourceResolver\Impl\ScalarValueResolver;
use lukaszmakuch\Haringo\ValueSourceResolver\Impl\ValueSourceResolverProxy;

/**
 * Builds the whole strategy of building things based
 * on BuildPlan objects.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuildingStrategyBuilder
{
    private $classSourceMap;
    private $methodSelectorMap;
    private $paramSelectorMap;
    private $classPathResolver;
    private $methodMatcher;
    private $parameterMatcher;
    private $valueResolver;
    private $paramListGenerator;
    
    /**
     * @var BuildingStrategyValueSourceExtension
     */
    private $valueSourceExtensions;
    
    public function __construct()
    {
        //maps
        $this->classSourceMap = new ClassPathSourceMap();
        $this->methodSelectorMap = new MethodSelectorMap();
        $this->paramSelectorMap = new ParamSelectorMap();
        //extensions
        $this->valueSourceExtensions = [];
    }
    
    public function setClassSourceMap(ClassPathSourceMap $map)
    {
        $this->classSourceMap = $map;
    }
    
    public function setMethodSelectorMap(MethodSelectorMap $map)
    {
        $this->methodSelectorMap = $map;
    }
    
    public function setParamSelectorMap(ParamSelectorMap $map)
    {
        $this->paramSelectorMap = $map;
    }
    
    public function addValueSourceExtension(BuildingStrategyValueSourceExtension $extension)
    {
        $this->valueSourceExtensions[] = $extension;
    }
    
    /**
     * @return BuildingStrategy
     */
    public function buildObjectBuildingStrategy()
    {
        $strategy = new BuildingStrategyProxy();
        
        //resolvers and matchers
        $this->classPathResolver = new ClassPathResolverProxy();
        $this->methodMatcher = new MethodMatcherProxy();
        $this->parameterMatcher = new ParameterMatcherProxy();
        $this->valueResolver = new ValueSourceResolverProxy();

        //register class path resolvers
        $this->classPathResolver->registerResolver(
            new ExactClassPathResolver(),
            ExactClassPath::class
        );
        $this->classPathResolver->registerResolver(
            new ClassPathFromMapResolver(
                $this->classSourceMap,
                $this->classPathResolver
            ),
            ClassPathFromMap::class
        );

        //register method matchers
        $this->methodMatcher->registerMatcher(
            new ConstructorSelectorMatcher(), 
            ConstructorSelector::class
        );
        $this->methodMatcher->registerMatcher(
            new MethodByExactNameMatcher(), 
            MethodByExactName::class
        );
        $this->methodMatcher->registerMatcher(
            new MethodFromMapMatcher(
                $this->methodSelectorMap,
                $this->classPathResolver,
                $this->methodMatcher 
            ), 
            MethodFromMap::class
        );

        //register parameter matchers
        $this->parameterMatcher->registerMatcher(
            new ParamByExactNameMatcher(),
            ParamByExactName::class
        );
        $this->parameterMatcher->registerMatcher(
            new ParamByPositionMatcher(),
            ParamByPosition::class
        );
        $this->parameterMatcher->registerMatcher(
            new ParamFromMapMatcher(
                $this->paramSelectorMap,
                $this->classPathResolver,
                $this->methodMatcher,
                $this->parameterMatcher
            ),
            ParamFromMap::class
        );
        
        //register value resolvers
        $this->valueResolver->registerResolver(
            new ScalarValueResolver(),
            ScalarValue::class
        );
        $this->valueResolver->registerResolver(
            new ArrayValueResolver($this->valueResolver),
            ArrayValue::class
        );
        $this->valueResolver->registerResolver(
            new BuildPlanResultValueResolver($strategy),
            BuildPlanResultValue::class
        );
        foreach ($this->valueSourceExtensions as $extension) {
            $this->valueResolver->registerResolver(
                $extension->getResolver(),
                $extension->getSupportedValueSourceClass()
            );
        }
        
        //param list generator
        $this->paramListGenerator = new ParameterListGenerator(
            $this->parameterMatcher,
            $this->valueResolver
        );
        
        //register actual building strategies 
        $strategy->registerStrategy(
            new NewInstanceBuildingStrategy(
                $this->classPathResolver, 
                $this->methodMatcher, 
                $this->paramListGenerator
            ),
            NewInstanceBuildPlan::class
        );
        $strategy->registerStrategy(
            new FactoryObjectProductBuildingStrategy(
                $this->classPathResolver, 
                $this->methodMatcher, 
                $this->paramListGenerator,
                $this->valueResolver
            ),
            FactoryObjectProductBuildPlan::class
        );
        $strategy->registerStrategy(
            new BuilderObjectProductBuildingStrategy(
                $this->classPathResolver, 
                $this->methodMatcher, 
                $this->paramListGenerator,
                $this->valueResolver
            ),
            BuilderObjectProductBuildPlan::class
        );
        $strategy->registerStrategy(
            new StaticFactoryProductBuildingStrategy(
                $this->classPathResolver, 
                $this->methodMatcher, 
                $this->paramListGenerator
            ),
            StaticFactoryProductBuildPlan::class
        );
        return $strategy;
    }
}
