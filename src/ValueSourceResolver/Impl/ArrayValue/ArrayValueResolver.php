<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ValueSourceResolver\Impl\ArrayValue;

use lukaszmakuch\Haringo\ValueSource\Impl\ArrayValue;
use lukaszmakuch\Haringo\ValueSourceResolver\ValueResolver;
use lukaszmakuch\Haringo\ValueSource\ValueSource;

/**
 * Resolves array value sources.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ArrayValueResolver implements ValueResolver
{
    private $actualResolver;
    
    /**
     * Provides resolver of value sources that are stored within given array value
     * sources.
     * 
     * @param ValueResolver $actualResolver
     */
    public function __construct(ValueResolver $actualResolver)
    {
        $this->actualResolver = $actualResolver;
    }
    
    public function resolveValueFrom(ValueSource $source)
    {
        /* @var $source ArrayValue */
        $result = [];
        foreach ($source->getAllSources() as $key => $valueSource) {
            $result[$key] = $this->actualResolver->resolveValueFrom($valueSource);
        }

        return $result;
    }
}
