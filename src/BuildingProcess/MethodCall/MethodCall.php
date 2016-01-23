<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall;

use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\ParameterValueWithSelector;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\Selector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\ValueSource\ValueSource;
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
     * @return MethodCall self
     */
    public function assignParamValue(
        ParameterSelector $selector, 
        ValueSource $valueSource
    );
    
    /**
     * @return ParameterValueWithSelector[]
     */
    public function getParamsValueWithSelectors();
}
