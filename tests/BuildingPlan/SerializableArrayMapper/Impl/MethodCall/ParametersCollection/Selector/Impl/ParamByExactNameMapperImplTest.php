<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelector\ArrayMapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\MethodCallMapper\ParametersCollection\Selector\Impl\ParamByExactNameMapperImpl;

class ParamByExactNameMapperImplTest extends ArrayMapperTest
{
    protected $mapper;
    
    protected function setUp()
    {
        $this->mapper = new ParamByExactNameMapperImpl();
    }
    
    public function testCorrectMapping()
    {
        $selectorToMap = new ParamByExactName("myParamName");
        
        $mappedSelector = $this->mapper->mapToArray($selectorToMap);
        $this->assertAllowedDataTypesWithin($mappedSelector);
        
        /* @var $rebuiltSelector ParamByExactName */
        $rebuiltSelector = $this->mapper->mapToObject($mappedSelector);
        $this->assertInstanceOf(ParamByExactName::class, $rebuiltSelector);
        $this->assertEquals("myParamName", $rebuiltSelector->getExactName());
    }
    
    /**
     * @expectedException \lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToMapObject
     */
    public function testWrongClass()
    {
        $this->mapper->mapToArray($this->getMock(ParameterSelector::class));
    }
    
    /**
     * @expectedException \lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToBuildFromArray
     */
    public function testIncorrectInputArray()
    {
        $this->mapper->mapToObject([123]);
    }
}
