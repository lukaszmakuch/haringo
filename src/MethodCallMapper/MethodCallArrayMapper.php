<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodCallMapper;

use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\Mapper\SerializableArrayMapper;

/**
 * Maps method calls to arrays.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MethodCallArrayMapper implements SerializableArrayMapper
{
    private $methodSelectorMapper;
    private $assignedParamValueMapper;
   
    /**
     * Provides dependencies.
     * 
     * @param SerializableArrayMapper $methodSelectorMapper used to map
     * method selectors.
     * 
     * @param SerializableArrayMapper $assignedParamValueMapper used to map 
     * parameter values
     */
    public function __construct(
        SerializableArrayMapper $methodSelectorMapper,
        SerializableArrayMapper $assignedParamValueMapper
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