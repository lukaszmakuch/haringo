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
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Builder\Extension\ValueSourceExtension;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\BuildPlanSerializer;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Impl\BuildPlanSerializerImpl;
use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ClassPathFromMap;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\ClassSourceMapper\Impl\ClassPathFromMapMapper;
use lukaszmakuch\ObjectBuilder\ClassSourceMapper\Impl\ExactClassPathArrayMapper;
use lukaszmakuch\ObjectBuilder\Mapper\Impl\ArrayMapperProxy;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodCallMapper\MethodCallArrayMapper;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\MethodFromMap;
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use lukaszmakuch\ObjectBuilder\MethodSelectorMapper\Impl\ConstructorSelectorArrayMapper;
use lukaszmakuch\ObjectBuilder\MethodSelectorMapper\Impl\MethodByExactNameArrayMapperImpl;
use lukaszmakuch\ObjectBuilder\MethodSelectorMapper\Impl\MethodFromMapMapper;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamFromMap;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl\ParamByExactNameMapperImpl;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl\ParamByPositionMapper;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl\ParamFromMapMapper;
use lukaszmakuch\ObjectBuilder\ParamValueMapper\AssignedParamValueArrayMapper;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ArrayValue;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanResultValue;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\ArrayValueMapper;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\BuildPlanResultValue\BuildPlanResultValueMapper;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\ScalarValueMapper;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Builder\Extension\SerializerValueSourceExtension;

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
