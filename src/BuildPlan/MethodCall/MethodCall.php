<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\MethodSelector;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ParameterValueWithSelector;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;

class MethodCall
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
        $valWithSelector = new ParameterValueWithSelector($valueSource, $selector);
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
