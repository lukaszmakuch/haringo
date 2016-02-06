<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\ArrayValue;

use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ArrayValue;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\ValueResolver;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

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
