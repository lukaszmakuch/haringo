<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\Builder\Impl;

use lukaszmakuch\Haringo\Builder\Extension\ValueSourceExtension;
use lukaszmakuch\Haringo\Builder\HaringoBuilder;
use lukaszmakuch\Haringo\BuildingStrategyBuilder\BuildingStrategyBuilder;
use lukaszmakuch\Haringo\BuildingStrategyBuilder\Extension\BuildingStrategyValueSourceExtension;
use lukaszmakuch\Haringo\BuildPlanSerializer\Builder\BuildPlanSerializerBuilder;
use lukaszmakuch\Haringo\BuildPlanSerializer\Builder\Extension\SerializerValueSourceExtensionImpl;
use lukaszmakuch\Haringo\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\Haringo\Impl\HaringoImpl;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\MethodSelectorMap;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;

/**
 * Default object buider builder implementation.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class HaringoBuilderImpl implements HaringoBuilder
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
        return new HaringoImpl(
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

    public function addValueSourceExtension(ValueSourceExtension $ext)
    {
        $this->addValSourceExtToSerializer($ext);
        $this->addValSourceExtToBuildingStrategy($ext);
    }
    
    private function addValSourceExtToSerializer(ValueSourceExtension $ext)
    {
        $serializerExt = $this->getSerializerValSourceExtBasedOn($ext);
        $this->serializerBuilder->addValueSourceExtension($serializerExt);
    }
    
    private function addValSourceExtToBuildingStrategy(ValueSourceExtension $ext)
    {
        $buildingStrategyExt = $this->getBuildingStrategyValSourceExtBasedOn($ext);
        $this->buildingStrategyBuilder->addValueSourceExtension($buildingStrategyExt);
    }
    
    /**
     * Converts a general value source extension into an extension accepted by
     * the mapper.
     * 
     * @param ValueSourceExtension $ext
     * @return SerializerValueSourceExtensionImpl
     */
    private function getSerializerValSourceExtBasedOn(ValueSourceExtension $ext)
    {
        return new SerializerValueSourceExtensionImpl(
            $ext->getMapper(),
            $ext->getSupportedValueSourceClass(),
            $ext->getUniqueExtensionId()
        );
    }
    
    /**
     * Converts a general value source extension into an extension accepted by
     * the building strategy.
     * 
     * @param ValueSourceExtension $ext
     * @return BuildingStrategyValueSourceExtension
     */
    private function getBuildingStrategyValSourceExtBasedOn(ValueSourceExtension $ext)
    {
        return new BuildingStrategyValueSourceExtension(
            $ext->getResolver(),
            $ext->getSupportedValueSourceClass()
        );
    }
}
