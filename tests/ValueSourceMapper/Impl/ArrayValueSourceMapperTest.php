<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\ArrayValueSourceMapper;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\ScalarValueMapper;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ArrayValueSource;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\ArrayValueSource\ArrayValueSourceResolver;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\ScalarValueResolver;

class ArrayValueSourceMapperTest  extends ArrayMapperTest
{
    public function testCorrectMapping()
    {
        $source = new ArrayValueSource();
        $source->addValue("oneHasStringIndex", new ScalarValue(1));
        $source->addValue(7, new ScalarValue("sevenWasTheKey"));
        
        $mapper = new ArrayValueSourceMapper(new ScalarValueMapper());
        $resolver = new ArrayValueSourceResolver(new ScalarValueResolver());
        
        $mappedSource = $mapper->mapToArray($source);
        $this->assertAllowedDataTypesWithin($mappedSource);
        /* @var $rebuiltSource ArrayValueSource */
        $rebuiltSource = $mapper->mapToObject($mappedSource);
        $this->assertEquals(
            [
                "oneHasStringIndex" => 1,
                7 => "sevenWasTheKey",
            ],
            $resolver->resolveValueFrom($rebuiltSource)
        );
    }
}
