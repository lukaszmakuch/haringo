<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelector\Matcher\Impl\ParamFromMapMatcher;

use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\Impl\MethodSelectorFromMap\FullMethodIdentifier;

class FullParamIdentifier
{
    private $methodId;
    private $actualParamSelector;
    public function __construct(
        FullMethodIdentifier $fullMethodIdentifier,
        ParameterSelector $actualParamSelector
    ) {
        $this->methodId = $fullMethodIdentifier;
        $this->actualParamSelector = $actualParamSelector;
    }
    
    /**
     * @return FullMethodIdentifier
     */
    public function getFullMethodIdentifier()
    {
        return $this->methodId;
    }
    
    /**
     * @return ParameterSelector
     */
    public function getActualParamSelector()
    {
        return $this->actualParamSelector;
    }
}