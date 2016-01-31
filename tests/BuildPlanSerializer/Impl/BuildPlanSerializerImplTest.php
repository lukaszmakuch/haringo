<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Impl;

use lukaszmakuch\ObjectBuilder\ArrayStringMapper\ArrayStringMapper;
use lukaszmakuch\ObjectBuilder\ArrayStringMapper\Exception\UnableToMapToArray;
use lukaszmakuch\ObjectBuilder\ArrayStringMapper\Exception\UnableToMapToString;
use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlanMapper\BuildPlanMapper;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Exception\UnableToSerialize;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Impl\BuildPlanSerializerImpl;
use lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToMapObject;
use PHPUnit_Framework_TestCase;

class BuildPlanSerializerImplTest extends PHPUnit_Framework_TestCase
{
    protected $planToMap;
    protected $planBuildArrayMapper;
    protected $arrayStringMapper;
    protected $serializer;
    
    protected function setUp()
    {
        $this->planToMap = $this->getMockBuilder(BuildPlan::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->planBuildArrayMapper = $this->getMockBuilder(BuildPlanMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->arrayStringMapper = $this->getMock(ArrayStringMapper::class);
        
        $this->serializer = new BuildPlanSerializerImpl(
            $this->planBuildArrayMapper,
            $this->arrayStringMapper
        );
    }
    
    public function testCorrectSerialization()
    {
        $planMappedAsArray = ["mapped plan"];
        $planArrayAsString = "serialized plan";
        
        $this->planBuildArrayMapper->method("mapToArray")->will($this->returnValueMap([
            [$this->planToMap, $planMappedAsArray]
        ]));
        $this->planBuildArrayMapper->method("mapToObject")->will($this->returnValueMap([
            [$planMappedAsArray, $this->planToMap]
        ]));
        
        $this->arrayStringMapper->method("arrayToString")->will($this->returnValueMap([
            [$planMappedAsArray, $planArrayAsString]
        ]));
        $this->arrayStringMapper->method("stringToArray")->will($this->returnValueMap([
            [$planArrayAsString, $planMappedAsArray]
        ]));
        
        $serializedBuildPlan = $this->serializer->serialize($this->planToMap);
        $deserializedPlan = $this->serializer->deserialize($serializedBuildPlan);
        $this->assertTrue($deserializedPlan === $this->planToMap);
    }
    
    public function testIncorrectMappingToString()
    {
        $this->planBuildArrayMapper
            ->method("mapToArray")
            ->will($this->returnValue([]));
        
        $this->arrayStringMapper
            ->method("arrayToString")
            ->will($this->throwException(new UnableToMapToString()));
        
        $this->setExpectedException(UnableToSerialize::class);
        
        $this->serializer->serialize($this->planToMap);
    }
    
    public function testIncorrectMappingToArray()
    {
        $this->planBuildArrayMapper
            ->method("mapToArray")
            ->will($this->throwException(new ImpossibleToMapObject()));
        
        $this->setExpectedException(UnableToSerialize::class);
        
        $this->serializer->serialize($this->planToMap);
    }
    
    public function testIncorrectMappingFromString()
    {
        $this->arrayStringMapper
            ->method("stringToArray")
            ->will($this->throwException(new UnableToMapToArray()));
        
        $this->setExpectedException(UnableToSerialize::class);
        
        $this->serializer->deserialize("");
    }
    
    public function testIncorrectMappingToObject()
    {
        $this->arrayStringMapper->method("stringToArray")->will($this->returnValue([]));
        
        $this->planBuildArrayMapper
            ->method("mapToObject")
            ->will($this->throwException(new ImpossibleToBuildFromArray()));
        
        $this->setExpectedException(UnableToSerialize::class);
        
        $this->serializer->deserialize("");
    }
}
