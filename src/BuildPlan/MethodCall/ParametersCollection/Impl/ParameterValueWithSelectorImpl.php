<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ParameterValueWithSelector;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;

class ParameterValueWithSelectorImpl implements ParameterValueWithSelector
{
    private $valueSource;
    private $selector;
    
    public function __construct(ValueSource $valueSource, ParameterSelector $selector)
    {
        $this->valueSource = $valueSource;
        $this->selector = $selector;
    }
    
    /**
     * @return ValueSource
     */
    public function getValueSource()
    {
        return $this->valueSource;
    }
    
    /**
     * @return ParameterSelector
     */
    public function getSelector()
    {
        return $this->selector;
    }
}
