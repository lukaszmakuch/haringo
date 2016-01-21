<?php

use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\Selector\Impl\ParamByExactName;

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

class ParamByExactNameTest extends PHPUnit_Framework_TestCase
{
    public function testHoldingValue()
    {
        $selector = new ParamByExactName("abc");
        $this->assertEquals("abc", $selector->getExactName());
    }
}
