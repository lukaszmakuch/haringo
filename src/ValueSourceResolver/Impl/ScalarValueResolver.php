<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ValueSourceResolver\Impl;

use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;
use lukaszmakuch\Haringo\ValueSourceResolver\ValueResolver;
use lukaszmakuch\Haringo\ValueSource\ValueSource;

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