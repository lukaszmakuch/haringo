<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceResolver;

use lukaszmakuch\ObjectBuilder\ValueSource\ObjectSource;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Exception\ImpossibleToResolveValue;

/**
 * Represents resolver of a value source that will always give an object.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ObjectResolver extends ValueResolver
{
    /**
     * @return mixed resolved object
     * @throws ImpossibleToResolveValue
     */
    public function resolveObjectFrom(ObjectSource $source);
}
