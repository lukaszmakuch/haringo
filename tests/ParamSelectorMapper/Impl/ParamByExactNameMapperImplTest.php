<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl\ParamByExactNameMapperImpl;

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
    
    public function testWrongClass()
    {
        $this->setExpectedException(ImpossibleToMapObject::class);
        $this->mapper->mapToArray($this->getMock(ParameterSelector::class));
    }
    
    public function testIncorrectInputArray()
    {
        $this->setExpectedException(ImpossibleToBuildFromArray::class);
        $this->mapper->mapToObject([123]);
    }
}
