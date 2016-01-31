<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\ArrayStringMapper\Impl\JSONArrayStringMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\BuilderObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\FactoryObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\StaticFactoryProductBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlanMapper\Impl\BuilderObjectProductBuildPlanMapper;
use lukaszmakuch\ObjectBuilder\BuildPlanMapper\Impl\FactoryObjectProductBuildPlanMapper;
use lukaszmakuch\ObjectBuilder\BuildPlanMapper\Impl\NewInstanceBuildPlanMapper;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Impl\BuildPlanSerializerImpl;
use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\ClassSourceMapper\Impl\ExactClassPathArrayMapper;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathFromMapResolver;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathResolverProxy;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ExactClassPathResolver;
use lukaszmakuch\ObjectBuilder\Mapper\Impl\ArrayMapperProxy;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodCallMapper\MethodCallArrayMapper;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\MethodSelectorFromMap;
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use lukaszmakuch\ObjectBuilder\MethodSelectorMapper\Impl\ConstructorSelectorArrayMapper;
use lukaszmakuch\ObjectBuilder\MethodSelectorMapper\Impl\ExactMethodNameArrayMapperImpl;
use lukaszmakuch\ObjectBuilder\MethodSelectorMapper\Impl\MethodSelectorFromMapMapper;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\ConstructorSelectorMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\ExactMethodNameMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodMatcherProxy;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodSelectorFromMap\MethodSelectorFromMapMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodSelectorFromMap\MethodSelectorMap;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamFromMap;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl\ParamByExactNameMapperImpl;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl\ParamByPositionMapper;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl\ParamFromMapMapper;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamByExactNameMatcher;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamByPositionMatcher;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParameterMatcherProxy;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamFromMapMatcher;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;
use lukaszmakuch\ObjectBuilder\ParamValueMapper\AssignedParamValueArrayMapper;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ArrayValueSource;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanValueSource;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\ArrayValueSourceMapper;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\BuildPlanValueSource\BuildPlanValueSourceMapper;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\ScalarValueMapper;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\ArrayValueSource\ArrayValueSourceResolver;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\BuildPlanValueSource\BuildPlanValueSourceResolver;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\ScalarValueResolver;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\ValueSourceResolverProxy;
use PHPUnit_Framework_TestCase;

abstract class BuilderTestTpl extends PHPUnit_Framework_TestCase
{
    protected $builder;
    protected $serializer;
    
    protected function setUp()
    {
        //build serialzier
        $planMapper = new ArrayMapperProxy(BuildPlan::class);
        $classSourceMapper = new ArrayMapperProxy(FullClassPathSource::class);
        $classSourceMapper->registerActualMapper(
            new ExactClassPathArrayMapper(),
            ExactClassPath::class,
            "exact_class_path"
        );
        $methodSelectorMapper = new ArrayMapperProxy(MethodSelector::class);
        $methodSelectorMapper->registerActualMapper(
            new ConstructorSelectorArrayMapper(),
            ConstructorSelector::class,
            "constructor"
        );
        $methodSelectorMapper->registerActualMapper(
            new ExactMethodNameArrayMapperImpl(),
            ExactMethodName::class,
            "exact_name"
        );
        $methodSelectorMapper->registerActualMapper(
            new MethodSelectorFromMapMapper(),
            MethodSelectorFromMap::class,
            "from_map"
        );
        $paramSelectorArrayMapper = new ArrayMapperProxy(ParameterSelector::class);
        $paramSelectorArrayMapper->registerActualMapper(
            new ParamByExactNameMapperImpl(),
            ParamByExactName::class,
            "name"
        );
        $paramSelectorArrayMapper->registerActualMapper(
            new ParamByPositionMapper(),
            ParamByPosition::class,
            "position"
        );
        $paramSelectorArrayMapper->registerActualMapper(
            new ParamFromMapMapper(),
            ParamFromMap::class,
            "map"
        );
        $valueSourceArrayMapper = new ArrayMapperProxy(ValueSource::class);
        $valueSourceArrayMapper->registerActualMapper(
            new ScalarValueMapper(),
            ScalarValue::class,
            "scalar"
        );
        $valueSourceArrayMapper->registerActualMapper(
            new ArrayValueSourceMapper($valueSourceArrayMapper),
            ArrayValueSource::class,
            "array"
        );
        $valueSourceArrayMapper->registerActualMapper(
            new BuildPlanValueSourceMapper($planMapper),
            BuildPlanValueSource::class,
            "build_plan"
        );
        $assignedParamValueMapper = new AssignedParamValueArrayMapper(
            $paramSelectorArrayMapper,
            $valueSourceArrayMapper
        );
        $methodCallMapper = new ArrayMapperProxy(MethodCall::class);
        $methodCallMapper->registerActualMapper(
            new MethodCallArrayMapper(
                $methodSelectorMapper,
                $assignedParamValueMapper
            ),
            MethodCall::class,
            "method_call"
        );
        $planMapper->registerActualMapper(
            new NewInstanceBuildPlanMapper(
                $classSourceMapper,
                $methodCallMapper
            ),
            NewInstanceBuildPlan::class,
            "new_instance"
        );
        $planMapper->registerActualMapper(
            new BuilderObjectProductBuildPlanMapper(
                $valueSourceArrayMapper,
                $methodCallMapper
            ),
            BuilderObjectProductBuildPlan::class,
            "builder"
        );
        $planMapper->registerActualMapper(
            new FactoryObjectProductBuildPlanMapper(
                $valueSourceArrayMapper,
                $methodCallMapper
            ),
            FactoryObjectProductBuildPlan::class,
            "factory_object"
        );
        $serializer = new BuildPlanSerializerImpl(
            $planMapper,
            new JSONArrayStringMapper()
        );
        $this->serializer = $serializer;
        
        $classPathMap = new ClassPathSourceMap();
        $methodMap = new MethodSelectorMap();
        $paramMap = new ParamSelectorMap();
        
        $builder = new BuilderProxy();
        $classPathResolver = new ClassPathResolverProxy();
        $classPathResolver->registerResolver(
            new ExactClassPathResolver(),
            ExactClassPath::class
        );
        $classPathResolver->registerResolver(
            new ClassPathFromMapResolver(
                $classPathMap, 
                $classPathResolver
            ),
            ExactClassPath::class
        );
        $methodMatcher = new MethodMatcherProxy();
        $methodMatcher->registerMatcher(
            new ConstructorSelectorMatcher(), 
            ConstructorSelector::class
        );
        $methodMatcher->registerMatcher(
            new ExactMethodNameMatcher(), 
            ExactMethodName::class
        );
        $methodMatcher->registerMatcher(
            new MethodSelectorFromMapMatcher(
                $methodMap,
                $classPathResolver,
                $methodMatcher 
            ), 
            MethodSelectorMap::class
        );
        
        $parameterMatcher = new ParameterMatcherProxy();
        $parameterMatcher->registerMatcher(
            new ParamByExactNameMatcher(),
            ParamByExactName::class
        );
        $parameterMatcher->registerMatcher(
            new ParamByPositionMatcher(),
            ParamByPosition::class
        );
        $parameterMatcher->registerMatcher(
            new ParamFromMapMatcher(
                $paramMap,
                $classPathResolver,
                $methodMatcher,
                $parameterMatcher
            ),
            ParamFromMap::class
        );
        $valueResolver = new ValueSourceResolverProxy();
        $valueResolver->registerResolver(
            new ScalarValueResolver(),
            ScalarValue::class
        );
        $valueResolver->registerResolver(
            new ArrayValueSourceResolver($valueResolver),
            ArrayValueSource::class
        );
        $valueResolver->registerResolver(
            new BuildPlanValueSourceResolver($builder),
            BuildPlanValueSource::class
        );
        $paramListGenerator = new ParameterListGenerator(
            $parameterMatcher,
            $valueResolver
        );
        
        $builder->registerBuilder(
            new NewInstanceBuilder(
                $classPathResolver, 
                $methodMatcher, 
                $paramListGenerator
            ),
            NewInstanceBuildPlan::class
        );
        $builder->registerBuilder(
            new FactoryObjectProductBuilder(
                $classPathResolver, 
                $methodMatcher, 
                $paramListGenerator,
                $valueResolver
            ),
            FactoryObjectProductBuildPlan::class
        );
        $builder->registerBuilder(
            new BuilderObjectProductBuilder(
                $classPathResolver, 
                $methodMatcher, 
                $paramListGenerator,
                $valueResolver
            ),
            BuilderObjectProductBuildPlan::class
        );
        
        
        $builder->registerBuilder(
            new StaticFactoryProductBuilder(
                $classPathResolver, 
                $methodMatcher, 
                $paramListGenerator
            ),
            StaticFactoryProductBuildPlan::class
        );
        $this->builder = $builder;
    }
    
    protected function getRebuiltObjectBy(BuildPlan $plan)
    {
        $serializedPlan = $this->serializer->serialize($plan);
        $this->assertTrue(is_string($serializedPlan));
        $deserializedPlan = $this->serializer->deserialize($serializedPlan);
        $builtObject = $this->builder->buildObjectBasedOn($deserializedPlan);
        return $builtObject;
    }
}
