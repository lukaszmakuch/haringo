<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodCallMapper\ParametersCollection\Selector\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByPosition;

class ParamByPositionMapperTest extends ArrayMapperTest
{
    public function testCorrectMapping()
    {
        $mapper = new ParamByPositionMapper();
        
        $selectorToMap = new ParamByPosition(123);
        
        $mappedSelector = $mapper->mapToArray($selectorToMap);
        $this->assertAllowedDataTypesWithin($mappedSelector);
        
        /* @var $rebuiltSelector ParamByPosition */
        $rebuiltSelector = $mapper->mapToObject($mappedSelector);
        $this->assertInstanceOf(ParamByPosition::class, $rebuiltSelector);
        $this->assertEquals(123, $rebuiltSelector->getParamPosFrom0());
    }
    
}
