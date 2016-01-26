<?php

use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl\ArrayStringMapper\Exception\UnableToMapToArray;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl\ArrayStringMapper\Impl\JSONArrayStringMapper;

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

class JSONArrayStringMapperTest extends PHPUnit_Framework_TestCase
{
    private $mapper;
    
    protected function setUp()
    {
        $this->mapper = new JSONArrayStringMapper();;
    }
    
    public function testCorrectMapping()
    {
        $arrayToMap = ["here" => "are", "some" => "values"];
        $mapped = $this->mapper->arrayToString($arrayToMap);
        $rebuilt = $this->mapper->stringToArray($mapped);
        $this->assertEquals($arrayToMap, $rebuilt);
    }
    
    public function testIncorrectJSON()
    {
        $this->setExpectedException(UnableToMapToArray::class);
        $this->mapper->stringToArray("not a JSON");
    }
}