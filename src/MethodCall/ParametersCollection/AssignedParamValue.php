<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodCall\ParametersCollection;

use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

class AssignedParamValue
{
    private $valueSource;
    private $selector;
    
    public function __construct(ParameterSelector $selector, ValueSource $valueSource)
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
