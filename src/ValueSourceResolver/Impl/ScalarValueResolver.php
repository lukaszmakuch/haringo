<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl;

use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\ValueResolver;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

/**
 * Resolves scalar values.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ScalarValueResolver implements ValueResolver
{
    public function resolveValueFrom(ValueSource $source)
    {
        /* @var $source ScalarValue */
        return $source->getHeldScalarValue();
    }
}