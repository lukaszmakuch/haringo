<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Resolver\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Resolver\Exception\UnsupportedSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Resolver\FullClassPathResolver;
use PHPUnit_Framework_TestCase;

class ClassPathSourceA implements FullClassPathSource {}
class ClassPathSourceB implements FullClassPathSource {}


class ClassPathResolverProxyTest extends PHPUnit_Framework_TestCase
{
    public function testResolvingClassPaths()
    {
        $classPathSourceA = new ClassPathSourceA();
        $classPathSourceB = new ClassPathSourceB();
        
        $resolvedSourceA = "a";
        $resolvedSourceB = "b";
        
        $resolverA = $this->getMock(FullClassPathResolver::class);
        $resolverA->method("resolve")->will($this->returnValueMap([
            [$classPathSourceA, $resolvedSourceA]
        ]));
        
        $resolverB = $this->getMock(FullClassPathResolver::class);
        $resolverB->method("resolve")->will($this->returnValueMap([
            [$classPathSourceB, $resolvedSourceB]
        ]));
        
        $resolver = new ClassPathResolverProxy();
        $resolver->registerResolver($resolverA, ClassPathSourceA::class);
        $resolver->registerResolver($resolverB, ClassPathSourceB::class);
        
        $this->assertEquals(
            $resolvedSourceA,
            $resolver->resolve($classPathSourceA)
        );
        $this->assertEquals(
            $resolvedSourceB,
            $resolver->resolve($classPathSourceB)
        );
    }
    
    public function testUndefinedActualResolver()
    {
        $this->setExpectedException(UnsupportedSource::class);
        $resolver = new ClassPathResolverProxy();
        $resolver->resolve(new ClassPathSourceA());
    }
}