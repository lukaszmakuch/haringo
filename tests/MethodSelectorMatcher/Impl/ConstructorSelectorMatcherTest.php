<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Exception\UnsupportedMatcher;
use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use lukaszmakuch\ObjectBuilder\TestClass;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

class ConstructorSelectorMatcherTest extends PHPUnit_Framework_TestCase
{
    public function testMatching()
    {
        $matcher = new ConstructorSelectorMatcher();
        $selector = ConstructorSelector::getInstance();
        
        $reflectedClass = new ReflectionClass(TestClass::class);
        $reflectedConstructor = $reflectedClass->getConstructor();
        $reflectedNormalMethod = $reflectedClass->getMethod("setMembers");
        
        $this->assertTrue(
            $matcher->methodMatches($reflectedConstructor, $selector)
        );
        
        $this->assertFalse(
            $matcher->methodMatches($reflectedNormalMethod, $selector)
        );
    }
    
    public function testExceptionWhenInvalidSelector()
    {
        $this->setExpectedException(UnsupportedMatcher::class);
        $matcher = new ConstructorSelectorMatcher();
        $reflectedClass = new ReflectionClass(TestClass::class);
        $reflectedConstructor = $reflectedClass->getConstructor();
        $matcher->methodMatches($reflectedConstructor, $this->getMock(MethodSelector::class));
    }
}
