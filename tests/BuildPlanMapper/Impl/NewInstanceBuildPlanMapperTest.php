<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlanMapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\ClassSourceMapper\ClassSourceMapper;
use lukaszmakuch\ObjectBuilder\MethodCallMapper\MethodCallArrayMapper;

class NewInstanceBuildPlanMapperTest extends ArrayMapperTest
{
    public function testCorrectMapping()
    {
        //build plan
        $classSource = $this->getMock(FullClassPathSource::class);
        $methodCall = $this->getMockBuilder(MethodCall::class)
            ->disableOriginalConstructor()
            ->getMock();
        $planToMap = new \lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan();
        $planToMap->setClassSource($classSource);
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
        $mapper = new NewInstanceBuildPlanMapper(
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
