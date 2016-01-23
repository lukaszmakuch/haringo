<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Resolver\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Impl\ScalarValue;
use PHPUnit_Framework_TestCase;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ScalarValueResolverTest extends PHPUnit_Framework_TestCase
{
    public function testResolvingScalarValue()
    {
        $source = new ScalarValue("abc");
        $resolver = new ScalarValueResolver();
        $this->assertEquals("abc", $resolver->resolveValueFrom($source));
    }
}
