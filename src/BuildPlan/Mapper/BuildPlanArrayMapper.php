<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Mapper;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Mapper\ClassSourceMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Mapper\MethodCallArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\MethodCall;

/**
 * Uses array like:
 * [
 *     array $mappedClassSource,
 *     [array $mappedMethodCall, array $mappedMethodCall, ...]
 * ]
 */
class BuildPlanArrayMapper
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
    
    public function mapToArray(BuildPlan $buildPlan)
    {
        return [
            $this->classSourceMapper->mapToArray($buildPlan->getClassSource()),
            array_map(function (MethodCall $call) {
                return $this->methodCallMapper->mapToArray($call);
            }, $buildPlan->getAllMethodCalls())
        ];
    }
    
    public function mapToObject(array $previouslyMappedArray)
    {
        $plan = new BuildPlan($this->classSourceMapper->mapToObject($previouslyMappedArray[0]));
        foreach ($previouslyMappedArray[1] as $callAsArray) {
            $plan->addMethodCall($this->methodCallMapper->mapToObject($callAsArray));
        }
        
        return $plan;
    }
}
