<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelector\Matcher\Impl;

use lukaszmakuch\ObjectBuilder\ParamSelector\Matcher\Impl\ParameterMatcherProxy;
use lukaszmakuch\ObjectBuilder\ParamSelector\Matcher\ParameterMatcher;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
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
        
        $matcherA = $this->getMatcherStub($reflectedParamA, $paramSelectorA);
        $matcherB = $this->getMatcherStub($reflectedParamB, $paramSelectorB);

        $proxy = new ParameterMatcherProxy();
        $proxy->registerMatcher($matcherA, ParamSelectorA::class);
        $proxy->registerMatcher($matcherB, ParamSelectorB::class);
        
        $this->assertTrue($proxy->parameterMatches($reflectedParamA, $paramSelectorA));
        $this->assertTrue($proxy->parameterMatches($reflectedParamB, $paramSelectorB));
    }
    
    private function getMatcherStub(
        ReflectionParameter $param,
        ParameterSelector $itsSelector
    ) {
        $matcher = $this->getMock(ParameterMatcher::class);
        $matcher->method("parameterMatches")->will($this->returnValueMap([
            [$param, $itsSelector, true]
        ]));
        $matcher->expects($this->once())
            ->method("parameterMatches")
            ->with($param);
        return $matcher;
    }
}
