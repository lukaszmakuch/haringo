<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Matcher\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Matcher\Impl\ParameterMatcherProxy;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Matcher\ParameterMatcher;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ParameterSelector;
use PHPUnit_Framework_TestCase;
use ReflectionParameter;

class ParamSelectorA implements ParameterSelector {}
class ParamSelectorB implements ParameterSelector {}

class ParameterMatcherProxyTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectMatch()
    {
        $reflectedParamA = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflectedParamB = $this->getMockBuilder(ReflectionParameter::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $paramSelectorA = new ParamSelectorA();
        $paramSelectorB = new ParamSelectorB();
        
        $matcherA = $this->getMatcherStub($reflectedParamA, $paramSelectorA, $reflectedParamA);
        $matcherB = $this->getMatcherStub($reflectedParamB, $paramSelectorB, $reflectedParamB);

        $proxy = new ParameterMatcherProxy();
        $proxy->registerMatcher($matcherA, ParamSelectorA::class);
        $proxy->registerMatcher($matcherB, ParamSelectorB::class);
        
        $this->assertTrue($proxy->parameterMatches($reflectedParamA, $paramSelectorA));
        $this->assertTrue($proxy->parameterMatches($reflectedParamB, $paramSelectorB));
    }
    
    private function getMatcherStub(
        ReflectionParameter $param,
        ParameterSelector $itsSelector,
        ReflectionParameter $paramItExpectsBeingUsedWith
    ) {
        $matcher = $this->getMock(ParameterMatcher::class);
        $matcher->method("parameterMatches")->will($this->returnValueMap([
            [$param, $itsSelector, true]
        ]));
        $matcher->expects($this->once())
            ->method("parameterMatches")
            ->with($paramItExpectsBeingUsedWith);
        return $matcher;
    }
}
