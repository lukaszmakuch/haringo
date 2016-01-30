<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodCall;

use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;
use lukaszmakuch\ObjectBuilder\MethodCall\ParametersCollection\AssignedParamValue;

class MethodCall
{
    private $selector;
    private $valueSourcesWithParamsSelectors = [];
    
    public function __construct(MethodSelector $selector)
    {
        $this->selector = $selector;
    }

    public function assignParamValue(
        AssignedParamValue $valueWithSelector
    ) {
        $this->valueSourcesWithParamsSelectors[] = $valueWithSelector;
        return $this;
    }

    public function getAssignedParamValues()
    {
        return $this->valueSourcesWithParamsSelectors;
    }

    public function getSelector()
    {
        return $this->selector;
    }

}
