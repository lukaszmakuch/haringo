<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ArrayMapper;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ArrayMapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ArrayMapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ParameterSelector;

interface ParamSelectorArrayMapper
{
    /**
     * @param ParameterSelector $selector
     * 
     * @return array may contain: scalar values, NULL, arrays and also arrays of 
     * any of these.
     * @throws ImpossibleToMapObject
     */
    public function mapToArray(ParameterSelector $selector);
    
    /**
     * @param array $previouslyMappedArray array that was the result of calling
     * the mapToArray method of this class.
     * 
     * @return ParameterSelector
     * @throws ImpossibleToBuildFromArray
     */
    public function mapToObject(array $previouslyMappedArray);
}