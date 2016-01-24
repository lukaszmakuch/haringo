<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Mapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;

class ScalarValueMapperTest extends ArrayMapperTest
{
    protected $mapper;
    
    protected function setUp()
    {
        $this->mapper = new ScalarValueMapper();
    }

    public function testCorrectMapping()
    {
        $valueSourceToMap = new ScalarValue("abc");
        $objectAsArray = $this->mapper->mapToArray($valueSourceToMap);
        $this->assertAllowedDataTypesWithin($objectAsArray);
        /* @var $rebuiltObject ScalarValue */
        $rebuiltObject = $this->mapper->mapToObject($objectAsArray);
        $this->assertInstanceOf(ScalarValue::class, $rebuiltObject);
        $this->assertEquals("abc", $rebuiltObject->getHeldScalarValue());
    }
    
    /**
     * @expectedException \lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Mapper\Exception\ImpossibleToMapObject
     */
    public function testWrongClass()
    {
        $this->mapper->mapToArray($this->getMock(ValueSource::class));
    }
    
    /**
     * @expectedException \lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Mapper\Exception\ImpossibleToBuildFromArray
     */
    public function testWrongIputArray()
    {
        $this->mapper->mapToObject([3 => 123]);
    }
}