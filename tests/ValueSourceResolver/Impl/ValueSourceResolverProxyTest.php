<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl;

use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Exception\ImpossibleToResolveValue;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\ValueSourceResolverProxy;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\ValueResolver;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

class TestValueSourceA implements ValueSource {}
class TestValueSourceB implements ValueSource {}

class ValueSourceResolverProxyTest extends \PHPUnit_Framework_TestCase
{
    public function testCorrectDelegation()
    {
        $valueSourceA = new TestValueSourceA();
        $valueSourceB = new TestValueSourceB();
        
        $resolverA = $this->getMock(ValueResolver::class);
        $resolverA->method("resolveValueFrom")->will($this->returnValueMap([
            [$valueSourceA, "a"]
        ]));
        
        $resolverB = $this->getMock(ValueResolver::class);
        $resolverB->method("resolveValueFrom")->will($this->returnValueMap([
            [$valueSourceB, "b"]
        ]));
        
        $proxy = new ValueSourceResolverProxy();
        $proxy->registerResolver($resolverA, TestValueSourceA::class);
        $proxy->registerResolver($resolverB, TestValueSourceB::class);
        
        $this->assertEquals("a", $proxy->resolveValueFrom($valueSourceA));
        $this->assertEquals("b", $proxy->resolveValueFrom($valueSourceB));
    }
    
    public function testUndefinedActualResolver()
    {
        $proxy = new ValueSourceResolverProxy();
        $this->setExpectedException(ImpossibleToResolveValue::class);
        $proxy->resolveValueFrom(new TestValueSourceA());
    }
}