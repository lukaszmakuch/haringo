<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlanMapper\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\FactoryObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\Mapper\SerializableArrayMapper;

class FactoryObjectProductBuildPlanMapper implements SerializableArrayMapper
{
    private $objectSourceMapper;
    private $methodCallMapper;
    
    public function __construct(
        SerializableArrayMapper $objectSourceMapper,
        SerializableArrayMapper $methodCallMapper
    ) {
        $this->objectSourceMapper = $objectSourceMapper;
        $this->methodCallMapper = $methodCallMapper;
    }
    
    public function mapToArray($objectToMap)
    {
        /* @var $objectToMap FactoryObjectProductBuildPlan */
        return [
            $this->objectSourceMapper->mapToArray($objectToMap->getFactoryObjectSource()),
            $this->methodCallMapper->mapToArray($objectToMap->getBuildMethodCall())
        ];
    }
    
    public function mapToObject(array $previouslyMappedObject)
    {
        $plan = new FactoryObjectProductBuildPlan(); 
        $plan->setFactoryObject($this->objectSourceMapper->mapToObject($previouslyMappedObject[0]));
        $plan->setBuildMethodCall($this->methodCallMapper->mapToObject($previouslyMappedObject[1]));
        return $plan;
    }
}