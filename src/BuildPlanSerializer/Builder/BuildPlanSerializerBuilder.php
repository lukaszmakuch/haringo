<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Builder;

use lukaszmakuch\ObjectBuilder\ArrayStringMapper\Impl\JSONArrayStringMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\BuilderObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\FactoryObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlanMapper\Impl\BuilderObjectProductBuildPlanMapper;
use lukaszmakuch\ObjectBuilder\BuildPlanMapper\Impl\FactoryObjectProductBuildPlanMapper;
use lukaszmakuch\ObjectBuilder\BuildPlanMapper\Impl\NewInstanceBuildPlanMapper;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Impl\BuildPlanSerializerImpl;
use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\ClassSourceMapper\Impl\ExactClassPathArrayMapper;
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
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamFromMap;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl\ParamByExactNameMapperImpl;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl\ParamByPositionMapper;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl\ParamFromMapMapper;
use lukaszmakuch\ObjectBuilder\ParamValueMapper\AssignedParamValueArrayMapper;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ArrayValueSource;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanValueSource;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\ArrayValueSourceMapper;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\BuildPlanValueSource\BuildPlanValueSourceMapper;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\ScalarValueMapper;

class BuildPlanSerializerBuilder
{
    private $planMapper;
    private $classSourceMapper;
    private $methodSelectorMapper;
    private $paramSelectorArrayMapper;
    private $methodCallMapper;
    
    /**
     * @return \lukaszmakuch\ObjectBuilder\BuildPlanSerializer\BuildPlanSerializer
     */
    public function buildSerializer()
    {
        // proxy objects of mappers
        $this->planMapper = new ArrayMapperProxy(BuildPlan::class);
        $this->classSourceMapper = new ArrayMapperProxy(FullClassPathSource::class);
        $this->methodSelectorMapper = new ArrayMapperProxy(MethodSelector::class);
        $this->paramSelectorArrayMapper = new ArrayMapperProxy(ParameterSelector::class);
        $this->valueSourceArrayMapper = new ArrayMapperProxy(ValueSource::class);
        $this->methodCallMapper = new ArrayMapperProxy(MethodCall::class);
        
        // class source mapper
        $this->classSourceMapper->registerActualMapper(
            new ExactClassPathArrayMapper(),
            ExactClassPath::class,
            "exact_class_path"
        );
        
        // method selector mapper
        $this->methodSelectorMapper = new ArrayMapperProxy(MethodSelector::class);
        $this->methodSelectorMapper->registerActualMapper(
            new ConstructorSelectorArrayMapper(),
            ConstructorSelector::class,
            "constructor"
        );
        $this->methodSelectorMapper->registerActualMapper(
            new ExactMethodNameArrayMapperImpl(),
            ExactMethodName::class,
            "exact_name"
        );
        $this->methodSelectorMapper->registerActualMapper(
            new MethodSelectorFromMapMapper(),
            MethodSelectorFromMap::class,
            "from_map"
        );
        
        // param selector mapper
        $this->paramSelectorArrayMapper->registerActualMapper(
            new ParamByExactNameMapperImpl(),
            ParamByExactName::class,
            "name"
        );
        $this->paramSelectorArrayMapper->registerActualMapper(
            new ParamByPositionMapper(),
            ParamByPosition::class,
            "position"
        );
        $this->paramSelectorArrayMapper->registerActualMapper(
            new ParamFromMapMapper(),
            ParamFromMap::class,
            "map"
        );
        
        // value source mapper
        $this->valueSourceArrayMapper->registerActualMapper(
            new ScalarValueMapper(),
            ScalarValue::class,
            "scalar"
        );
        $this->valueSourceArrayMapper->registerActualMapper(
            new ArrayValueSourceMapper($this->valueSourceArrayMapper),
            ArrayValueSource::class,
            "array"
        );
        $this->valueSourceArrayMapper->registerActualMapper(
            new BuildPlanValueSourceMapper($this->planMapper),
            BuildPlanValueSource::class,
            "build_plan"
        );
        
        // param value mapper
        $assignedParamValueMapper = new AssignedParamValueArrayMapper(
            $this->paramSelectorArrayMapper,
            $this->valueSourceArrayMapper
        );
        
        // method call mapper
        $this->methodCallMapper->registerActualMapper(
            new MethodCallArrayMapper(
                $this->methodSelectorMapper,
                $assignedParamValueMapper
            ),
            MethodCall::class,
            "method_call"
        );

        // different plan mappers
        $this->planMapper->registerActualMapper(
            new NewInstanceBuildPlanMapper(
                $this->classSourceMapper,
                $this->methodCallMapper
            ),
            NewInstanceBuildPlan::class,
            "new_instance"
        );
        $this->planMapper->registerActualMapper(
            new BuilderObjectProductBuildPlanMapper(
                $this->valueSourceArrayMapper,
                $this->methodCallMapper
            ),
            BuilderObjectProductBuildPlan::class,
            "builder"
        );
        $this->planMapper->registerActualMapper(
            new FactoryObjectProductBuildPlanMapper(
                $this->valueSourceArrayMapper,
                $this->methodCallMapper
            ),
            FactoryObjectProductBuildPlan::class,
            "factory_object"
        );
        
        // return serializer
        return new BuildPlanSerializerImpl(
            $this->planMapper,
            new JSONArrayStringMapper()
        );
    }
}
