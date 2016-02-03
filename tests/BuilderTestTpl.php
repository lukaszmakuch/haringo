<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder;

use lukaszmakuch\ObjectBuilder\Builder\Impl\ObjectBuilderBuilderImpl;
use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodSelectorFromMap\MethodSelectorMap;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;
use PHPUnit_Framework_TestCase;

abstract class BuilderTestTpl extends PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectBuilder
     */
    protected $builder;

    protected function setUp()
    {
        $objectBuilderBuilder = new ObjectBuilderBuilderImpl();
        $objectBuilderBuilder->setParamSelectorMap($this->getParamSelectorMap());
        $objectBuilderBuilder->setClassSourceMap($this->getClassSourceMap());
        $objectBuilderBuilder->setMethodSelectorMap($this->getMethodSelectorMap());
        $this->builder = $objectBuilderBuilder->build();
    }
    
    protected function getRebuiltObjectBy(BuildPlan $plan)
    {
        $serializedPlan = $this->builder->serializeBuildPlan($plan);
        $this->assertTrue(is_string($serializedPlan));
        $deserializedPlan = $this->builder->deserializeBuildPlan($serializedPlan);
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
    
    /**
     * @return ClassPathSourceMap
     */
    protected function getClassSourceMap()
    {
        return new ClassPathSourceMap();
    }
    
    /**
     * @return MethodSelectorMap
     */
    protected function getMethodSelectorMap()
    {
        return new MethodSelectorMap();
    }
}
