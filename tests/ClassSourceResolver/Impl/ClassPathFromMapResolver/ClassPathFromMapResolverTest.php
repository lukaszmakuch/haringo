<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathFromMapResolver;

use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ClassPathFromMap;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\FullClassPathResolver;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathFromMapResolver;
use PHPUnit_Framework_TestCase;

class ClassPathFromMapResolverTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectMap()
    {
        $actualSource = $this->getMock(FullClassPathSource::class);
        $resolvedActualSource = "resolved_actual_source";
        
        $actualResolver = $this->getMock(FullClassPathResolver::class);
        $actualResolver->method("resolve")->will($this->returnValueMap([
            [$actualSource, $resolvedActualSource]
        ]));
        
        $map = new ClassPathSourceMap();
        $map->addSource("mapped_source", $actualSource);
        
        $resolver = new ClassPathFromMapResolver(
            $map,
            $actualResolver
        );
        
        $this->assertEquals(
            $resolvedActualSource,
            $resolver->resolve(new ClassPathFromMap("mapped_source"))
        );
    }
}