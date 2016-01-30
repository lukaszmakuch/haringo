<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelector\Mapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\MethodSelectorMapper\Impl\ExactMethodNameArrayMapperImpl;

class ExactMethodNameArrayMapperImplTest extends ArrayMapperTest
{
    public function testCorrectMapping()
    {
        $mapper = new ExactMethodNameArrayMapperImpl();
        
        $selectorToMap = new ExactMethodName("abc");
        
        $selectorAsArray = $mapper->mapToArray($selectorToMap);
        
        $rebuiltSelector = $mapper->mapToObject($selectorAsArray);
        
        $this->assertInstanceOf(ExactMethodName::class, $rebuiltSelector);
        /* @var $rebuiltSelector ExactMethodName */
        $this->assertEquals("abc", $rebuiltSelector->getExactMethodName());
    }
}