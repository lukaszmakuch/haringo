<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;
use PHPUnit_Framework_TestCase;


class AssignedParamValueTest extends PHPUnit_Framework_TestCase
{
    public function testHoldingValues()
    {
        $valueSource = $this->getMock(ValueSource::class);
        $selector = $this->getMock(ParameterSelector::class);
        $paramWithSelector = new AssignedParamValue($selector, $valueSource);
        $this->assertTrue($valueSource === $paramWithSelector->getValueSource());
        $this->assertTrue($selector === $paramWithSelector->getSelector());
    }
}
