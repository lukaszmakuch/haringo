<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\MethodMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use PHPUnit_Framework_TestCase;
use ReflectionMethod;

class MethodSelectorA implements MethodSelector {}
class MethodSelectorB implements MethodSelector {}

class MethodMatcherProxyTest extends PHPUnit_Framework_TestCase
{
    public function testMatching()
    {
        $reflectedMethodA = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        $reflectedMethodB = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $methodSelectorA = new MethodSelectorA();
        $methodSelectorB = new MethodSelectorB();
        
        $matcherA = $this->getMatcherStub($reflectedMethodA, $methodSelectorA);
        $matcherB = $this->getMatcherStub($reflectedMethodB, $methodSelectorB);

        $proxy = new MethodMatcherProxy();
        $proxy->registerMatcher($matcherA, MethodSelectorA::class);
        $proxy->registerMatcher($matcherB, MethodSelectorB::class);
        
        $this->assertTrue($proxy->methodMatches($reflectedMethodA, $methodSelectorA));
        $this->assertTrue($proxy->methodMatches($reflectedMethodB, $methodSelectorB));
    }
    
    private function getMatcherStub(
        ReflectionMethod $method,
        MethodSelector $itsSelector
    ) {
        $matcher = $this->getMock(MethodMatcher::class);
        $matcher->method("methodMatches")->will($this->returnValueMap([
            [$method, $itsSelector, true]
        ]));
        $matcher->expects($this->once())
            ->method("methodMatches")
            ->with($method);
        return $matcher;
    }
}