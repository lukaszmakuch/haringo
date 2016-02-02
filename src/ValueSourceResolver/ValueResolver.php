<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceResolver;

use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Exception\ImpossibleToResolveValue;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

/**
 * Resolves values from some given sources.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ValueResolver
{
    /**
     * Resolves value from the given source.
     * 
     * @return mixed resolved value
     * @throws ImpossibleToResolveValue
     */
    public function resolveValueFrom(ValueSource $source);
}
