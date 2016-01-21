<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\ValueSource\Impl;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ScalarValueTest extends \PHPUnit_Framework_TestCase
{
    public function testHoldingValue()
    {
        $scalarValueSource = new ScalarValue("abc");
        $this->assertEquals("abc", $scalarValueSource->getHeldScalarValue());
    }
}
