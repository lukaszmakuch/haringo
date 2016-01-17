<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall;

use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\MethodParametersCollection;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\Selector\MethodSelector;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface MethodCall
{
    /**
     * @return MethodSelector
     */
    public function getSelector();
    
    /**
     * @return MethodParametersCollection
     */
    public function getParametersCollection();
}
