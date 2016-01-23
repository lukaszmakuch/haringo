<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\Impl;

use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\Selector\MethodSelector;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\Impl\ParameterValueWithSelectorImpl;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\Selector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\ValueSource\ValueSource;

class MethodCallImpl implements MethodCall
{
    private $selector;
    private $valueSourcesWithParamsSelectors = [];
    
    public function __construct(MethodSelector $selector)
    {
        $this->selector = $selector;
    }

    public function assignParamValue(
        ParameterSelector $selector, 
        ValueSource $valueSource
    ) {
        $valWithSelector = new ParameterValueWithSelectorImpl($valueSource, $selector);
        $this->valueSourcesWithParamsSelectors[] = $valWithSelector;
        return $this;
    }

    public function getParamsValueWithSelectors()
    {
        return $this->valueSourcesWithParamsSelectors;
    }

    public function getSelector()
    {
        return $this->selector;
    }

}
