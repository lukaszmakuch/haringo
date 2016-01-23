<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ParameterValueWithSelector
{
    /**
     * @return ValueSource
     */
    public function getValueSource();
    
    /**
     * @return ParameterSelector
     */
    public function getSelector();
}
