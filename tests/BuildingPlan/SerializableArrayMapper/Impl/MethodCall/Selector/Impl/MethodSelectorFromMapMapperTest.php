<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelector\Mapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\MethodSelectorFromMap;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\Selector\Impl\MethodSelectorFromMapMapper;

class MethodSelectorFromMapMapperTest extends ArrayMapperTest
{
    public function testCorrectMapping()
    {
        $mapper = new MethodSelectorFromMapMapper();
        
        $selectorToMap = new MethodSelectorFromMap("key");
        
        $selectorAsArray = $mapper->mapToArray($selectorToMap);
        
        /* @var $rebuiltSelector MethodSelectorFromMap */
        $rebuiltSelector = $mapper->mapToObject($selectorAsArray);
        
        $this->assertInstanceOf(MethodSelectorFromMap::class, $rebuiltSelector);
        $this->assertEquals("key", $rebuiltSelector->getKeyFromMap());
    }
}
