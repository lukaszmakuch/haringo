<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\Builder\ObjectBuilderBuilder;
use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Builder\BuildPlanSerializerBuilder;
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
}
