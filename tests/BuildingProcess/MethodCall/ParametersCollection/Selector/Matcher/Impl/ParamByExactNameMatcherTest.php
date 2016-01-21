<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\Selector\Matcher\Impl;

use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\Selector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\TestClass;
use PHPUnit_Framework_TestCase;
use ReflectionParameter;

class ParamByExactNameMatcherTest extends PHPUnit_Framework_TestCase
{

    private $matcher;
    
    protected function setUp()
    {
        $this->matcher = new ParamByExactNameMatcher();
    }
    
    public function testCorrectMatch()
    {
        $this->assertTrue($this->matcher->parameterMatches(
            new ReflectionParameter([TestClass::class, "setMemberA"], "newA"), 
            new ParamByExactName("newA")
        ));
    }

}
