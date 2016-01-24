<?php

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Mapper\BuildPlanArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl\ArrayStringMapper\ArrayStringMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl\BuildPlanSerializerImpl;

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

class BuildPlanSerializerImplTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectSerialization()
    {
        $planToMap = $this->getMockBuilder(BuildPlan::class)
            ->disableOriginalConstructor()
            ->getMock();
        $planMappedAsArray = ["mapped plan"];
        $planArrayAsString = "serialized plan";
        //plan build mapper
        $planBuildArrayMapper = $this->getMockBuilder(BuildPlanArrayMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $planBuildArrayMapper->method("mapToArray")->will($this->returnValueMap([
            [$planToMap, $planMappedAsArray]
        ]));
        $planBuildArrayMapper->method("mapToObject")->will($this->returnValueMap([
            [$planMappedAsArray, $planToMap]
        ]));
        //array to string mapper
        $arrayStringMapper = $this->getMock(ArrayStringMapper::class);
        $arrayStringMapper->method("arrayToString")->will($this->returnValueMap([
            [$planMappedAsArray, $planArrayAsString]
        ]));
        $arrayStringMapper->method("stringToArray")->will($this->returnValueMap([
            [$planArrayAsString, $planMappedAsArray]
        ]));
        
        $serializer = new BuildPlanSerializerImpl(
            $planBuildArrayMapper,
            $arrayStringMapper
        );
        
        $serializedBuildPlan = $serializer->serialize($planToMap);
        $deserializedPlan = $serializer->deserialize($serializedBuildPlan);
        $this->assertTrue($deserializedPlan === $planToMap);
    }
}
