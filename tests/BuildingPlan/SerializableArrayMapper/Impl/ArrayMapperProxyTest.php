<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Mapper\Impl;

use lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\Mapper\SerializableArrayMapper;
use PHPUnit_Framework_TestCase;
use stdClass;

class BaseClass {}
class MappedClassA extends BaseClass {}
class MappedClassB extends BaseClass {}

class ArrayMapperProxyTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectMapping()
    {
        $objectA = new MappedClassA();
        $mappedObjectA = ["a"];
        $objectB = new MappedClassB();
        $mappedObjectB = ["b"];
        
        $actualMapperA = $this->getMapperStub($objectA, $mappedObjectA);
        $actualMapperB = $this->getMapperStub($objectB, $mappedObjectB);
        
        $mapperProxy = new ArrayMapperProxy(BaseClass::class);
        $mapperProxy->registerActualMapper(
            $actualMapperA,
            MappedClassA::class,
            "uniqueMapperIdA"
        );
        $mapperProxy->registerActualMapper(
            $actualMapperB,
            MappedClassB::class,
            "uniqueMapperIdB"
        );
        
        //check A
        $rebuiltObjectA = $mapperProxy->mapToObject($mapperProxy->mapToArray($objectA));
        $this->assertTrue($objectA === $rebuiltObjectA);
        
        //check B
        $rebuiltObjectB = $mapperProxy->mapToObject($mapperProxy->mapToArray($objectB));
        $this->assertTrue($objectB === $rebuiltObjectB);
    }
    
    public function testWrongObjectClass()
    {
        $this->setExpectedException(ImpossibleToMapObject::class);
        $mapperProxy = new ArrayMapperProxy(BaseClass::class);
        $mapperProxy->mapToArray(new stdClass());
    }
    
    public function testUnsupportedType()
    {
        $this->setExpectedException(ImpossibleToMapObject::class);
        $mapperProxy = new ArrayMapperProxy(BaseClass::class);
        $mapperProxy->mapToArray(new MappedClassA());
    }
    
    /**
     * @param BaseClass $object
     * @param array $mappedObject
     * @return SerializableArrayMapper
     */
    private function getMapperStub($object, $mappedObject)
    {
        $mapper = $this->getMock(SerializableArrayMapper::class);
        $mapper->method("mapToArray")->will($this->returnValueMap([
            [$object, $mappedObject]
        ]));
        $mapper->method("mapToObject")->will($this->returnValueMap([
            [$mappedObject, $object]
        ]));
        return $mapper;
    }
}
