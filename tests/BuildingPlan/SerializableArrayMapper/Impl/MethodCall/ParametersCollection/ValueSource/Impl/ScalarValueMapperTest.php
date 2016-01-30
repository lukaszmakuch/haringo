<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSource\Mapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\MethodCallMapper\ParametersCollection\ValueSource\Impl\ScalarValueMapper;

class ScalarValueMapperTest extends ArrayMapperTest
{
    protected $mapper;
    
    protected function setUp()
    {
        $this->mapper = new ScalarValueMapper();
    }

    public function testCorrectMapping()
    {
        $valueSourceToMap = new ScalarValue(123);
        $objectAsArray = $this->mapper->mapToArray($valueSourceToMap);
        $this->assertAllowedDataTypesWithin($objectAsArray);
        /* @var $rebuiltObject ScalarValue */
        $rebuiltObject = $this->mapper->mapToObject($objectAsArray);
        $this->assertInstanceOf(ScalarValue::class, $rebuiltObject);
        $this->assertEquals(123, $rebuiltObject->getHeldScalarValue());
    }
    
    /**
     * @expectedException \lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Exception\ImpossibleToMapObject
     */
    public function testWrongClass()
    {
        $this->mapper->mapToArray($this->getMock(ValueSource::class));
    }
    
    /**
     * @expectedException \lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Exception\ImpossibleToBuildFromArray
     */
    public function testWrongIputArray()
    {
        $this->mapper->mapToObject([3 => 123]);
    }
}