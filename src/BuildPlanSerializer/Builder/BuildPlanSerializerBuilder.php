<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildPlanSerializer\Builder;

use lukaszmakuch\Haringo\ArrayStringMapper\Impl\JSONArrayStringMapper;
use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\BuilderObjectProductBuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\FactoryObjectProductBuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\BuildPlanMapper\Impl\BuilderObjectProductBuildPlanMapper;
use lukaszmakuch\Haringo\BuildPlanMapper\Impl\FactoryObjectProductBuildPlanMapper;
use lukaszmakuch\Haringo\BuildPlanMapper\Impl\NewInstanceBuildPlanMapper;
use lukaszmakuch\Haringo\BuildPlanSerializer\Builder\Extension\ValueSourceExtension;
use lukaszmakuch\Haringo\BuildPlanSerializer\BuildPlanSerializer;
use lukaszmakuch\Haringo\BuildPlanSerializer\Impl\BuildPlanSerializerImpl;
use lukaszmakuch\Haringo\ClassSource\FullClassPathSource;
use lukaszmakuch\Haringo\ClassSource\Impl\ClassPathFromMap;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\ClassSourceMapper\Impl\ClassPathFromMapMapper;
use lukaszmakuch\Haringo\ClassSourceMapper\Impl\ExactClassPathArrayMapper;
use lukaszmakuch\Haringo\Mapper\Impl\ArrayMapperProxy;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\MethodCallMapper\MethodCallArrayMapper;
use lukaszmakuch\Haringo\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodFromMap;
use lukaszmakuch\Haringo\MethodSelector\MethodSelector;
use lukaszmakuch\Haringo\MethodSelectorMapper\Impl\ConstructorSelectorArrayMapper;
use lukaszmakuch\Haringo\MethodSelectorMapper\Impl\MethodByExactNameArrayMapperImpl;
use lukaszmakuch\Haringo\MethodSelectorMapper\Impl\MethodFromMapMapper;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamFromMap;
use lukaszmakuch\Haringo\ParamSelector\ParameterSelector;
use lukaszmakuch\Haringo\ParamSelectorMapper\Impl\ParamByExactNameMapperImpl;
use lukaszmakuch\Haringo\ParamSelectorMapper\Impl\ParamByPositionMapper;
use lukaszmakuch\Haringo\ParamSelectorMapper\Impl\ParamFromMapMapper;
use lukaszmakuch\Haringo\ParamValueMapper\AssignedParamValueArrayMapper;
use lukaszmakuch\Haringo\ValueSource\Impl\ArrayValue;
use lukaszmakuch\Haringo\ValueSource\Impl\BuildPlanResultValue;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;
use lukaszmakuch\Haringo\ValueSource\ValueSource;
use lukaszmakuch\Haringo\ValueSourceMapper\Impl\ArrayValueMapper;
use lukaszmakuch\Haringo\ValueSourceMapper\Impl\BuildPlanResultValue\BuildPlanResultValueMapper;
use lukaszmakuch\Haringo\ValueSourceMapper\Impl\ScalarValueMapper;
use lukaszmakuch\Haringo\BuildPlanSerializer\Builder\Extension\SerializerValueSourceExtension;

/**
 * Builds the whole serializer with all modules.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuildPlanSerializerBuilder
{
    private $planMapper;
    private $classSourceMapper;
    private $methodSelectorMapper;
    private $paramSelectorArrayMapper;
    private $methodCallMapper;
    
    /**
     * @var SerializerValueSourceExtension 
     */
    private $valueSourceExtensions = [];
    
    public function addValueSourceExtension(SerializerValueSourceExtension $extension)
    {
        $this->valueSourceExtensions[] = $extension;
    }
    
    /**
     * @return BuildPlanSerializer
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
        $this->classSourceMapper->registerActualMapper(
            new ClassPathFromMapMapper(),
            ClassPathFromMap::class,
            "from_map"
        );
        
        // method selector mapper
        $this->methodSelectorMapper = new ArrayMapperProxy(MethodSelector::class);
        $this->methodSelectorMapper->registerActualMapper(
            new ConstructorSelectorArrayMapper(),
            ConstructorSelector::class,
            "constructor"
        );
        $this->methodSelectorMapper->registerActualMapper(
            new MethodByExactNameArrayMapperImpl(),
            MethodByExactName::class,
            "exact_name"
        );
        $this->methodSelectorMapper->registerActualMapper(
            new MethodFromMapMapper(),
            MethodFromMap::class,
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
            new ArrayValueMapper($this->valueSourceArrayMapper),
            ArrayValue::class,
            "array"
        );
        $this->valueSourceArrayMapper->registerActualMapper(
            new BuildPlanResultValueMapper($this->planMapper),
            BuildPlanResultValue::class,
            "build_plan"
        );
        foreach ($this->valueSourceExtensions as $valueSourceExtension) {
            $this->valueSourceArrayMapper->registerActualMapper(
                $valueSourceExtension->getMapper(),
                $valueSourceExtension->getSupportedValueSourceClass(),
                $valueSourceExtension->getUniqueExtensionId()
            );
        }
        
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
