<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher;

use lukaszmakuch\Haringo\ParamSelector\ParameterSelector;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\FullMethodIdentifier;

/**
 * Represents full parameter identifier, that is:
 * - class identifier
 * - method identifier
 * - param identifier
 * Those two first are part of full method identifier.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FullParamIdentifier
{
    private $methodId;
    private $actualParamSelector;
    
    /**
     * Provides dependencies.
     * 
     * @param FullMethodIdentifier $fullMethodIdentifier identifies the method
     * @param ParameterSelector $actualParamSelector identifies the actual parameter
     */
    public function __construct(
        FullMethodIdentifier $fullMethodIdentifier,
        ParameterSelector $actualParamSelector
    ) {
        $this->methodId = $fullMethodIdentifier;
        $this->actualParamSelector = $actualParamSelector;
    }
    
    /**
     * @return FullMethodIdentifier method this param belongs to
     */
    public function getFullMethodIdentifier()
    {
        return $this->methodId;
    }
    
    /**
     * @return ParameterSelector parameter (but not its method) selector
     */
    public function getActualParamSelector()
    {
        return $this->actualParamSelector;
    }
}