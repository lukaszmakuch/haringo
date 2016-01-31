<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Builder;

use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\BuilderObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\FactoryObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\StaticFactoryProductBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ClassPathFromMap;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathFromMapResolver;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathResolverProxy;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ExactClassPathResolver;
use lukaszmakuch\ObjectBuilder\Impl\BuilderObjectProductBuilder;
use lukaszmakuch\ObjectBuilder\Impl\BuilderProxy;
use lukaszmakuch\ObjectBuilder\Impl\FactoryObjectProductBuilder;
use lukaszmakuch\ObjectBuilder\Impl\NewInstanceBuilder;
use lukaszmakuch\ObjectBuilder\Impl\ParameterListGenerator;
use lukaszmakuch\ObjectBuilder\Impl\StaticFactoryProductBuilder;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\MethodSelectorFromMap;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\ConstructorSelectorMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\ExactMethodNameMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodMatcherProxy;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodSelectorFromMap\MethodSelectorFromMapMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodSelectorFromMap\MethodSelectorMap;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamFromMap;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamByExactNameMatcher;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamByPositionMatcher;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParameterMatcherProxy;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamFromMapMatcher;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ArrayValueSource;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanValueSource;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\ArrayValueSource\ArrayValueSourceResolver;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\BuildPlanValueSource\BuildPlanValueSourceResolver;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\ScalarValueResolver;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\ValueSourceResolverProxy;

class ObjectBuilderBuilder
{
    private $classSourceMap;
    private $methodSelectorMap;
    private $paramSelectorMap;
    private $classPathResolver;
    private $methodMatcher;
    private $parameterMatcher;
    private $valueResolver;
    private $paramListGenerator;
    
    public function __construct()
    {
        //maps
        $this->classSourceMap = new ClassPathSourceMap();
        $this->methodSelectorMap = new MethodSelectorMap();
        $this->paramSelectorMap = new ParamSelectorMap();
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
    
    public function buildObjectBuilder()
    {
        $builder = new BuilderProxy();
        
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
            new ExactMethodNameMatcher(), 
            ExactMethodName::class
        );
        $this->methodMatcher->registerMatcher(
            new MethodSelectorFromMapMatcher(
                $this->methodSelectorMap,
                $this->classPathResolver,
                $this->methodMatcher 
            ), 
            MethodSelectorFromMap::class
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
            new ArrayValueSourceResolver($this->valueResolver),
            ArrayValueSource::class
        );
        $this->valueResolver->registerResolver(
            new BuildPlanValueSourceResolver($builder),
            BuildPlanValueSource::class
        );
        
        //param list generator
        $this->paramListGenerator = new ParameterListGenerator(
            $this->parameterMatcher,
            $this->valueResolver
        );
        
        //register actual builders 
        $builder->registerBuilder(
            new NewInstanceBuilder(
                $this->classPathResolver, 
                $this->methodMatcher, 
                $this->paramListGenerator
            ),
            NewInstanceBuildPlan::class
        );
        $builder->registerBuilder(
            new FactoryObjectProductBuilder(
                $this->classPathResolver, 
                $this->methodMatcher, 
                $this->paramListGenerator,
                $this->valueResolver
            ),
            FactoryObjectProductBuildPlan::class
        );
        $builder->registerBuilder(
            new BuilderObjectProductBuilder(
                $this->classPathResolver, 
                $this->methodMatcher, 
                $this->paramListGenerator,
                $this->valueResolver
            ),
            BuilderObjectProductBuildPlan::class
        );
        $builder->registerBuilder(
            new StaticFactoryProductBuilder(
                $this->classPathResolver, 
                $this->methodMatcher, 
                $this->paramListGenerator
            ),
            StaticFactoryProductBuildPlan::class
        );
        return $builder;
    }
}
