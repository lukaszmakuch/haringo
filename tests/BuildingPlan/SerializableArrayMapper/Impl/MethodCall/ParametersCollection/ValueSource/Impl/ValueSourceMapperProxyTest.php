<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Mapper\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\ValueSource\Impl\ValueSourceMapperProxy;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\ValueSource\ValueSourceArrayMapper;
use PHPUnit_Framework_TestCase;

class TestValueSourceA implements ValueSource {}
class TestValueSourceB implements ValueSource {}

class ValueSourceMapperProxyTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectMapping()
    {
        $valueSourceA = new TestValueSourceA();
        $mappedValSourceA = ["a"];
        $valueSourceB = new TestValueSourceB();
        $mappedValSourceB = ["b"];
        
        $actualMapperA = $this->getMapperStub($valueSourceA, $mappedValSourceA);
        $actualMapperB = $this->getMapperStub($valueSourceB, $mappedValSourceB);
        
        $mapperProxy = new ValueSourceMapperProxy();
        $mapperProxy->registerActualMapper(
            $actualMapperA,
            TestValueSourceA::class,
            "uniqueMapperIdA"
        );
        $mapperProxy->registerActualMapper(
            $actualMapperB,
            TestValueSourceB::class,
            "uniqueMapperIdB"
        );
        
        //check A
        $rebuiltValSourceA = $mapperProxy->mapToObject($mapperProxy->mapToArray($valueSourceA));
        $this->assertTrue($valueSourceA === $rebuiltValSourceA);
        
        //check B
        var_dump($mapperProxy->mapToArray($valueSourceB));
        $rebuiltValSourceB = $mapperProxy->mapToObject($mapperProxy->mapToArray($valueSourceB));
        $this->assertTrue($valueSourceB === $rebuiltValSourceB);
    }
    
    /**
     * @param ValueSource $valSource
     * @param array $mappedValSource
     * @return ValueSource
     */
    private function getMapperStub($valSource, $mappedValSource)
    {
        $mapper = $this->getMock(ValueSourceArrayMapper::class);
        $mapper->method("mapToArray")->will($this->returnValueMap([
            [$valSource, $mappedValSource]
        ]));
        $mapper->method("mapToObject")->will($this->returnValueMap([
            [$mappedValSource, $valSource]
        ]));
        return $mapper;
    }
}
