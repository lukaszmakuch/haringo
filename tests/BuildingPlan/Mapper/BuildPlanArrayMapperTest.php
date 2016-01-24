<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Mapper;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Mapper\ClassSourceMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Mapper\MethodCallArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\MethodCall;

class BuildPlanArrayMapperTest extends ArrayMapperTest
{
    public function testCorrectMapping()
    {
        //build plan
        $classSource = $this->getMock(FullClassPathSource::class);
        $methodCall = $this->getMockBuilder(MethodCall::class)
            ->disableOriginalConstructor()
            ->getMock();
        $planToMap = new BuildPlan($classSource);
        $planToMap->addMethodCall($methodCall);
        
        //build mapper
        //class source mapper
        $mappedClassSource = ["mapper class source"];
        $classSourceMapper = $this->getMock(ClassSourceMapper::class);
        $classSourceMapper->method("mapToArray")->will($this->returnValueMap([
            [$classSource, $mappedClassSource]
        ]));
        $classSourceMapper->method("mapToObject")->will($this->returnValueMap([
            [$mappedClassSource, $classSource]
        ]));
        //method call mapper
        $mappedMethodCall = ["mapped method call"];
        $methodCallMapper = $this->getMockBuilder(MethodCallArrayMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $methodCallMapper->method("mapToArray")->will($this->returnValueMap([
            [$methodCall, $mappedMethodCall]
        ]));
        $methodCallMapper->method("mapToObject")->will($this->returnValueMap([
            [$mappedMethodCall, $methodCall]
        ]));
        $mapper = new BuildPlanArrayMapper(
            $classSourceMapper,
            $methodCallMapper
        );
        
        //check mapper
        $planAsArray = $mapper->mapToArray($planToMap);
        $rebuiltPlan = $mapper->mapToObject($planAsArray);
        $this->assertInstanceOf(BuildPlan::class, $rebuiltPlan);
        /* @var $rebuiltPlan BuildPlan */
        $this->assertTrue($rebuiltPlan->getClassSource() === $classSource);
        $this->assertTrue($rebuiltPlan->getAllMethodCalls()[0] === $methodCall);
    }
}