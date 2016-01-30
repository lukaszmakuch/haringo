<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl;

use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use PHPUnit_Framework_TestCase;

class ExactClassPathResolverTest extends PHPUnit_Framework_TestCase
{
    public function testResolvingValue()
    {
        $resolver = new ExactClassPathResolver();
        
        $valueToResolve = new ExactClassPath("abc");
        
        $this->assertEquals("abc", $resolver->resolve($valueToResolve));
    }
}
