<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodCallMapper;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use lukaszmakuch\ObjectBuilder\MethodCallMapper\ParametersCollection\AssignedParamValueArrayMapper;

class MethodCallArrayMapperTest extends ArrayMapperTest
{
    public function testCorrectMapping()
    {
        //call that is going to be mapped to array
        $selector = $this->getMock(MethodSelector::class);
        $selectorAsArray = ["mappedSelector"];
        $paramWithSelector = $this->getMockBuilder(AssignedParamValue::class)
            ->disableOriginalConstructor()
            ->getMock();
        $paramWithSelectorAsArray = ["assignedParamValue"];
        $callToMap = new MethodCall($selector);
        $callToMap->assignParamValue($paramWithSelector);
        
        //building mapper
        //method selector mapper
        $methodSelectorMapper = $this->getMock(Selector\MethodSelectorArrayMapper::class);
        $methodSelectorMapper->method("mapToArray")->will($this->returnValueMap([
            [$selector, $selectorAsArray]
        ]));
        $methodSelectorMapper->method("mapToObject")->will($this->returnValueMap([
            [$selectorAsArray, $selector]
        ]));
        //param mapper
        $assignedParamValueMapper = $this->getMockBuilder(AssignedParamValueArrayMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $assignedParamValueMapper->method("mapToArray")->will($this->returnValueMap([
            [$paramWithSelector, $paramWithSelectorAsArray]
        ]));
        $assignedParamValueMapper->method("mapToObject")->will($this->returnValueMap([
            [$paramWithSelectorAsArray, $paramWithSelector]
        ]));
        $mapper = new MethodCallArrayMapper(
            $methodSelectorMapper,
            $assignedParamValueMapper
        );
        
        //testing mapper
        $callAsArray = $mapper->mapToArray($callToMap);
        $rebuiltCall = $mapper->mapToObject($callAsArray);
        $this->assertInstanceOf(MethodCall::class, $rebuiltCall);
        /* @var $rebuiltCall MethodCall */
        $this->assertTrue($rebuiltCall->getAssignedParamValues()[0] === $paramWithSelector);
        $this->assertTrue($rebuiltCall->getSelector() === $selector);
    }
}
