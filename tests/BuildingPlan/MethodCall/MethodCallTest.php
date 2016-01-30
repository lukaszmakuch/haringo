<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodCall;

use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use PHPUnit_Framework_TestCase;

class MethodCallTest extends PHPUnit_Framework_TestCase
{
    public function testHoldingValues()
    {
        $selector = $this->getMock(MethodSelector::class);
        $selectorA = $this->getMock(ParameterSelector::class);
        $selectorB = $this->getMock(ParameterSelector::class);
        $valueSourceA = $this->getMock(ValueSource::class);
        $valueSourceB = $this->getMock(ValueSource::class);
        $call = new MethodCall($selector);
        $call->assignParamValue(new AssignedParamValue($selectorA, $valueSourceA));
        $call->assignParamValue(new AssignedParamValue($selectorB, $valueSourceB));
        
        $heldParamsWithSelectors = $call->getAssignedParamValues();
        $this->assertTrue(
            (count($heldParamsWithSelectors) == 2)
            && (
                (
                    ($heldParamsWithSelectors[0]->getSelector() === $selectorA)
                    && ($heldParamsWithSelectors[0]->getValueSource() === $valueSourceA)
                )
                && (
                    ($heldParamsWithSelectors[1]->getSelector() === $selectorB)
                    && ($heldParamsWithSelectors[1]->getValueSource() === $valueSourceB)
                )
            )
        );
    }
}
