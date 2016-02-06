<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildPlanMapper\Impl;

use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\Mapper\SerializableArrayMapper;
use lukaszmakuch\Haringo\MethodCall\MethodCall;

/**
 * Maps build plans of new class instances.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class NewInstanceBuildPlanMapper implements SerializableArrayMapper
{
    private $classSourceMapper;
    private $methodCallMapper;
    
    public function __construct(
        SerializableArrayMapper $classSourceMapper,
        SerializableArrayMapper $methodCallMapper
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
        $classSource = $this->classSourceMapper->mapToObject($previouslyMappedObject[0]);
        $plan = new NewInstanceBuildPlan();
        $plan->setClassSource($classSource);
        foreach ($previouslyMappedObject[1] as $callAsArray) {
            $methodCall = $this->methodCallMapper->mapToObject($callAsArray);
            $plan->addMethodCall($methodCall);
        }
        
        return $plan;
    }
}
