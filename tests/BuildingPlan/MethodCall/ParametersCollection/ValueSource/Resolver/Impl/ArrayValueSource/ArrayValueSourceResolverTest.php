<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSource\Resolver\Impl\ArrayValueSource;

use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ArrayValueSource;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\ValueSource\Resolver\Impl\ScalarValueResolver;
use PHPUnit_Framework_TestCase;

class ArrayValueSourceResolverTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectResolving()
    {
        $resolver = new ArrayValueSourceResolver(new ScalarValueResolver());
        
        $arrayValueSource = new ArrayValueSource();
        $arrayValueSource->addValue(42, new ScalarValue("forty-two"));
        $arrayValueSource->addValue("integer", new ScalarValue(123));
        
        $expectedValue = [
            42 => "forty-two",
            "integer" => 123,
        ];
        
        $this->assertEquals(
            $expectedValue,
            $resolver->resolveValueFrom($arrayValueSource)
        );
    }
}
