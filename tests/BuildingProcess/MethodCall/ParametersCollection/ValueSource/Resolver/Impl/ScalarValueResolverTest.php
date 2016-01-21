<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\ValueSource\Resolver\Impl;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ScalarValueResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testResolvingScalarValue()
    {
        $source = new \lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\ValueSource\Impl\ScalarValue("abc");
        $resolver = new ScalarValueResolver();
        $this->assertEquals("abc", $resolver->resolveValueFrom($source));
    }
}
