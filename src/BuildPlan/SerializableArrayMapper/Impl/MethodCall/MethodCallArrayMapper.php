<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\AssignedParamValueArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\Selector\MethodSelectorArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\SerializableArrayMapper;

/**
 * Uses structure like:
 * [
 *     array $serializedMethodSelector,
 *     [array $serializedAssignedParamValue, array $serializedAssignedParamValue, ...]
 * ]
 */
class MethodCallArrayMapper implements SerializableArrayMapper
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
    
    public function mapToArray($objectToMap)
    {
        /* @var $call MethodCall */
        $call = $objectToMap;
        return [
            $this->methodSelectorMapper->mapToArray($call->getSelector()),
            array_map(function (AssignedParamValue $param) {
                return $this->assignedParamValueMapper->mapToArray($param);
            }, $call->getAssignedParamValues())
        ];
    }
    
    public function mapToObject(array $previouslyMappedObject)
    {
        $methodSelector = $this->methodSelectorMapper->mapToObject($previouslyMappedObject[0]);
        $call = new MethodCall($methodSelector);
        foreach ($previouslyMappedObject[1] as $assignedParamValAsArray) {
            $call->assignParamValue(
                $this->assignedParamValueMapper->mapToObject($assignedParamValAsArray)
            );
        }
        
        return $call;
    }
}