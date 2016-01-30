<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\ScalarValueMapper;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToBuildFromArray;

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
    
    public function testWrongClass()
    {
        $this->setExpectedException(ImpossibleToMapObject::class);
        $this->mapper->mapToArray($this->getMock(ValueSource::class));
    }
    
    public function testWrongIputArray()
    {
        $this->setExpectedException(ImpossibleToBuildFromArray::class);
        $this->mapper->mapToObject([3 => 123]);
    }
}