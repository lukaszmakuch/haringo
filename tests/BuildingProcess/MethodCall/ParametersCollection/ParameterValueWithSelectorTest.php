<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;
use PHPUnit_Framework_TestCase;


class ParameterValueWithSelectorTest extends PHPUnit_Framework_TestCase
{
    public function testHoldingValues()
    {
        $valueSource = $this->getMock(ValueSource::class);
        $selector = $this->getMock(ParameterSelector::class);
        $paramWithSelector = new ParameterValueWithSelector($valueSource, $selector);
        $this->assertTrue($valueSource === $paramWithSelector->getValueSource());
        $this->assertTrue($selector === $paramWithSelector->getSelector());
    }
}
