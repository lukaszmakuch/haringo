<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ValueSourceResolver;

use lukaszmakuch\Haringo\ValueSourceResolver\Exception\ImpossibleToResolveValue;
use lukaszmakuch\Haringo\ValueSource\ValueSource;

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
