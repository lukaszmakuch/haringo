<?php

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamFromMap;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\Selector\Impl\ParamFromMapMapper;

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

class ParamFromMapMapperTest extends ArrayMapperTest
{
    private $mapper;
    
    protected function setUp()
    {
        $this->mapper = new ParamFromMapMapper();
    }
    
    public function testCorrectMapping()
    {
        $selectorToMap = new ParamFromMap("param_key");
        
        $mappedSelector = $this->mapper->mapToArray($selectorToMap);
        $this->assertAllowedDataTypesWithin($mappedSelector);
        
        /* @var $rebuiltSelector ParamFromMap */
        $rebuiltSelector = $this->mapper->mapToObject($mappedSelector);
        $this->assertInstanceOf(ParamFromMap::class, $rebuiltSelector);
        $this->assertEquals("param_key", $rebuiltSelector->getKeyFromMap());
    }
}