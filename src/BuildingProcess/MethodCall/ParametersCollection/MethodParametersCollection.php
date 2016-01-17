<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection;

use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\Exception\ImpossibleToSetParameterValue;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\Selector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\ValueSource\ValueSource;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface MethodParametersCollection
{
    /**
     * @return null
     * @throws ImpossibleToSetParameterValue
     */
    public function setParameterValue(
        ParameterSelector $selector, 
        ValueSource $valueSource
    );
}
