<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Matcher\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Impl\ParamByPosition;
use lukaszmakuch\ObjectBuilder\TestClass;
use PHPUnit_Framework_TestCase;
use ReflectionParameter;

class ParamByPositionMatcherTest  extends PHPUnit_Framework_TestCase
{
    public function testCorrectMatching()
    {
        $matcher = new ParamByPositionMatcher();
        
        $secondParamSelector = new ParamByPosition(1);
        
        $secondParamOfMethod = new ReflectionParameter(
            [TestClass::class, "setMembers"],
            "newB"
        );
        
        $this->assertTrue($matcher->parameterMatches(
            $secondParamOfMethod,
            $secondParamSelector
        ));
    }
}
