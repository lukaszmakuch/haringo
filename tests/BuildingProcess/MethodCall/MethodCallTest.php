<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall;

use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\Selector\MethodSelector;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\Selector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\ValueSource\ValueSource;
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
        $call->assignParamValue($selectorA, $valueSourceA);
        $call->assignParamValue($selectorB, $valueSourceB);
        
        $heldParamsWithSelectors = $call->getParamsValueWithSelectors();
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
