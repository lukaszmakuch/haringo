<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Builder\Impl;

use lukaszmakuch\ObjectBuilder\Builder\ObjectBuilderBuilder;
use lukaszmakuch\ObjectBuilder\BuildingStrategyBuilder\BuildingStrategyBuilder;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Builder\BuildPlanSerializerBuilder;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\ObjectBuilder\Impl\ObjectBuilderImpl;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodSelectorFromMap\MethodSelectorMap;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;

/**
 * Default object buider builder implementation.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ObjectBuilderBuilderImpl implements ObjectBuilderBuilder
{
    /**
     * @var BuildPlanSerializerBuilder used to build serializer
     */
    private $serializerBuilder;
    
    /**
     * @var BuildingStrategyBuilder used to build strategy of building objects
     * based on BuildPlan objects
     */
    private $buildingStrategyBuilder;
    
    /**
     * Creates builders used to build components of the main builder.
     */
    public function __construct()
    {
        $this->serializerBuilder = new BuildPlanSerializerBuilder();
        $this->buildingStrategyBuilder = new BuildingStrategyBuilder();
    }
    
    public function build()
    {
        return new ObjectBuilderImpl(
            $this->serializerBuilder->buildSerializer(), 
            $this->buildingStrategyBuilder->buildObjectBuildingStrategy()
        );
    }

    public function setClassSourceMap(ClassPathSourceMap $map)
    {
        $this->buildingStrategyBuilder->setClassSourceMap($map);
    }

    public function setMethodSelectorMap(MethodSelectorMap $map)
    {
        $this->buildingStrategyBuilder->setMethodSelectorMap($map);
    }

    public function setParamSelectorMap(ParamSelectorMap $map)
    {
        $this->buildingStrategyBuilder->setParamSelectorMap($map);
    }
}
