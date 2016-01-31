<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelector\Mapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\MethodSelectorMapper\Impl\ConstructorSelectorArrayMapper;

class ConstructorSelectorArrayMapperTest extends ArrayMapperTest
{
    public function testCorrectMapping()
    {
        $mapper = new ConstructorSelectorArrayMapper();
        
        $selectorToMap = ConstructorSelector::getInstance();
        
        $selectorAsArray = $mapper->mapToArray($selectorToMap);
        
        $rebuiltSelector = $mapper->mapToObject($selectorAsArray);
        
        $this->assertInstanceOf(ConstructorSelector::class, $rebuiltSelector);
    }
}