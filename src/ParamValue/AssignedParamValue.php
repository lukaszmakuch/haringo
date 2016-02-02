<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamValue;

use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

/**
 * Holds parameter selector and value source together.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class AssignedParamValue
{
    private $valueSource;
    private $selector;
   
    /**
     * Provides values that should be held.
     * 
     * @param ParameterSelector $selector
     * @param ValueSource $valueSource
     */
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
