<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ValueSource\Impl;

use lukaszmakuch\Haringo\ValueSource\ValueSource;

/**
 * Represents a value source that will always be resolved to a scalar value.
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ScalarValue implements ValueSource
{
    private $heldScalarValue;
    
    public function __construct($scalarValueToHold)
    {
        $this->heldScalarValue = $scalarValueToHold;
    }
    
    public function getHeldScalarValue()
    {
        return $this->heldScalarValue;
    }
}
