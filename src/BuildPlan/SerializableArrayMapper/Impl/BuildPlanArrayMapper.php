<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\FullClassPathSource\ClassSourceMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\MethodCallArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\SerializableArrayMapper;

/**
 * Uses array like:
 * [
 *     array $mappedClassSource,
 *     [array $mappedMethodCall, array $mappedMethodCall, ...]
 * ]
 */
class BuildPlanArrayMapper implements SerializableArrayMapper
{
    private $classSourceMapper;
    private $methodCallMapper;
    
    public function __construct(
    ClassSourceMapper $classSourceMapper,
        MethodCallArrayMapper $methodCallMapper
    ) {
        $this->classSourceMapper = $classSourceMapper;
        $this->methodCallMapper = $methodCallMapper;
    }
    
    public function mapToArray($objectToMap)
    {
        /* @var $buildPlan BuildPlan */
        $buildPlan = $objectToMap;
        return [
            $this->classSourceMapper->mapToArray($buildPlan->getClassSource()),
            array_map(function (MethodCall $call) {
                return $this->methodCallMapper->mapToArray($call);
            }, $buildPlan->getAllMethodCalls())
        ];
    }
    
    public function mapToObject(array $previouslyMappedObject)
    {
        $plan = new BuildPlan($this->classSourceMapper->mapToObject($previouslyMappedObject[0]));
        foreach ($previouslyMappedObject[1] as $callAsArray) {
            $plan->addMethodCall($this->methodCallMapper->mapToObject($callAsArray));
        }
        
        return $plan;
    }
}
