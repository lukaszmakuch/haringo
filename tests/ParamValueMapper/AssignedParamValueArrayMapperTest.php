<?php

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\ParamValueMapper\AssignedParamValueArrayMapper;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\ParamSelectorArrayMapper;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\ValueSourceArrayMapper;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

class AssignedParamValueArrayMapperTest extends ArrayMapperTest
{
    public function testCorrectMap()
    {
        //fake selector and value source
        $selector = $this->getMock(ParameterSelector::class);
        $correctSelectorMappingResult = ["mappedSelector"];
        
        $valueSource = $this->getMock(ValueSource::class);
        $correctValueSourceMappingResult = ["mappedValueSource"];
        
        //stubs of mappers that support these fakes
        $paramSelectorMapper = $this->getMock(ParamSelectorArrayMapper::class);
        $paramSelectorMapper->method("mapToArray")->will($this->returnValueMap([
            [$selector, $correctSelectorMappingResult]
        ]));
        $paramSelectorMapper->method("mapToObject")->will($this->returnValueMap([
            [$correctSelectorMappingResult, $selector]
        ]));
        
        $valueSourceMapper = $this->getMock(ValueSourceArrayMapper::class);
        $valueSourceMapper->method("mapToArray")->will($this->returnValueMap([
            [$valueSource, $correctValueSourceMappingResult]
        ]));
        $valueSourceMapper->method("mapToObject")->will($this->returnValueMap([
            [$correctValueSourceMappingResult, $valueSource]
        ]));

        //object to map - the value source assigned to the param selector
        $assignedParamValueToMap = new AssignedParamValue(
            $selector,
            $valueSource
        );
        
        //mapper which uses previously created stubs
        $mapper = new AssignedParamValueArrayMapper(
            $paramSelectorMapper,
            $valueSourceMapper
        );
        
        //check the mapper
        $assignedParamValAsArray = $mapper->mapToArray($assignedParamValueToMap);
        $this->assertAllowedDataTypesWithin($assignedParamValAsArray);
        $rebuiltObject = $mapper->mapToObject($assignedParamValAsArray);
        $this->assertInstanceOf(AssignedParamValue::class, $rebuiltObject);
        $this->assertTrue($rebuiltObject->getSelector() === $selector);
        $this->assertTrue($rebuiltObject->getValueSource() === $valueSource);
    }
}