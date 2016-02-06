<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\MethodCall;

use lukaszmakuch\Haringo\MethodSelector\MethodSelector;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;

/**
 * Single call of a method selected by some selector and triggered with 
 * some parameters (also selected by selectors) with some values (resolved as
 * late as possible).
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MethodCall
{
    private $selector;
    private $valueSourcesWithParamsSelectors = [];
    
    /**
     * Provides dependency.
     * 
     * @param MethodSelector $selector used to select the method
     */
    public function __construct(MethodSelector $selector)
    {
        $this->selector = $selector;
    }

    /**
     * Assign some value to some param.
     * 
     * @param AssignedParamValue $valueWithSelector
     * @return MethodCall
     */
    public function assignParamValue(
        AssignedParamValue $valueWithSelector
    ) {
        $this->valueSourcesWithParamsSelectors[] = $valueWithSelector;
        return $this;
    }

    /**
     * @return AssignedParamValue[]
     */
    public function getAssignedParamValues()
    {
        return $this->valueSourcesWithParamsSelectors;
    }

    /**
     * @return MethodSelector
     */
    public function getSelector()
    {
        return $this->selector;
    }
}
