<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Mapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\Selector\Impl\ExactMethodNameArrayMapperImpl;

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