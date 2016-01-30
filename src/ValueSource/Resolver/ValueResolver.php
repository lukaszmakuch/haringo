<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSource\Resolver;

use lukaszmakuch\ObjectBuilder\ValueSource\Resolver\Exception\ImpossibleToResolveValue;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ValueResolver
{
    /**
     * @return mixed resolved value
     * @throws ImpossibleToResolveValue
     */
    public function resolveValueFrom(ValueSource $source);
}
