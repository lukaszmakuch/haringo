<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\ArrayValueSource;

use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ArrayValueSource;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\ValueResolver;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

class ArrayValueSourceResolver implements ValueResolver
{
    private $actualResolver;
    
    public function __construct(ValueResolver $actualResolver)
    {
        return $this->actualResolver = $actualResolver;
    }
    
    public function resolveValueFrom(ValueSource $source)
    {
        /* @var $source ArrayValueSource */
        $result = [];
        foreach ($source->getAllSources() as $key => $valueSource) {
            $result[$key] = $this->actualResolver->resolveValueFrom($valueSource);
        }

        return $result;
    }
}
