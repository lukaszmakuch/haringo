<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder;

use lukaszmakuch\ObjectBuilder\Builder\ObjectBuilderBuilder;
use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Builder\BuildPlanSerializerBuilder;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodSelectorFromMap\MethodSelectorMap;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;
use PHPUnit_Framework_TestCase;

abstract class BuilderTestTpl extends PHPUnit_Framework_TestCase
{
    protected $builder;
    protected $serializer;

    protected function setUp()
    {
        $serializerBuilder = new BuildPlanSerializerBuilder();
        $this->serializer = $serializerBuilder->buildSerializer();
        
        $objectBuilderBuilder = new ObjectBuilderBuilder();
        $objectBuilderBuilder->setParamSelectorMap($this->getParamSelectorMap());
        $objectBuilderBuilder->setClassSourceMap($this->getClassSourceMap());
        $objectBuilderBuilder->setMethodSelectorMap($this->getMethodSelectorMap());
        $this->builder = $objectBuilderBuilder->buildObjectBuilder();
    }
    
    protected function getRebuiltObjectBy(BuildPlan $plan)
    {
        $serializedPlan = $this->serializer->serialize($plan);
        $this->assertTrue(is_string($serializedPlan));
        $deserializedPlan = $this->serializer->deserialize($serializedPlan);
        $builtObject = $this->builder->buildObjectBasedOn($deserializedPlan);
        return $builtObject;
    }
    
    /**
     * @return ParamSelectorMap
     */
    protected function getParamSelectorMap()
    {
        return new ParamSelectorMap();
    }
    
    protected function getClassSourceMap()
    {
        return new ClassPathSourceMap();
    }
    
    protected function getMethodSelectorMap()
    {
        return new MethodSelectorMap();
    }
}
