<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSource\Impl;

use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

/**
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
