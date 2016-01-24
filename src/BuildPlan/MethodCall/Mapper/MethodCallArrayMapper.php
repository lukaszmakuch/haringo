<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Mapper;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Mapper\AssignedParamValueArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Mapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Mapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Mapper\MethodSelectorArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\MethodSelector;

/**
 * Uses structure like:
 * [
 *     array $serializedMethodSelector,
 *     [array $serializedAssignedParamValue, array $serializedAssignedParamValue, ...]
 * ]
 */
class MethodCallArrayMapper
{
    private $methodSelectorMapper;
    private $assignedParamValueMapper;
    
    public function __construct(
        MethodSelectorArrayMapper $methodSelectorMapper,
        AssignedParamValueArrayMapper $assignedParamValueMapper
    ) {
        $this->methodSelectorMapper = $methodSelectorMapper;
        $this->assignedParamValueMapper = $assignedParamValueMapper;
    }
    
    /**
     * @return array
     * @throws ImpossibleToMapObject
     */
    public function mapToArray(MethodCall $call)
    {
        return [
            $this->methodSelectorMapper->mapToArray($call->getSelector()),
            array_map(function (AssignedParamValue $param) {
                return $this->assignedParamValueMapper->mapToArray($param);
            }, $call->getAssignedParamValues())
        ];
    }
    
    /**
     * 
     * @param array $previouslyMappedArray
     * 
     * @return MethodSelector
     * @throws ImpossibleToBuildFromArray
     */
    public function mapToObject(array $previouslyMappedArray)
    {
        $methodSelector = $this->methodSelectorMapper->mapToObject($previouslyMappedArray[0]);
        $call = new MethodCall($methodSelector);
        foreach ($previouslyMappedArray[1] as $assignedParamValAsArray) {
            $call->assignParamValue(
                $this->assignedParamValueMapper->mapToObject($assignedParamValAsArray)
            );
        }
        
        return $call;
    }
}