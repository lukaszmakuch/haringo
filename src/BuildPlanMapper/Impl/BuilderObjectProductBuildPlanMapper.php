<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlanMapper\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\BuilderObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\Mapper\SerializableArrayMapper;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;

class BuilderObjectProductBuildPlanMapper implements SerializableArrayMapper
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
        /* @var $objectToMap BuilderObjectProductBuildPlan */
        return [
            $this->objectSourceMapper->mapToArray($objectToMap->getBuilderSource()),
            array_map(function (MethodCall $call) {
                return $this->methodCallMapper->mapToArray($call);
            }, $objectToMap->getAllSettingMethodCalls()),
            $this->methodCallMapper->mapToArray($objectToMap->getBuildMethodCall())
        ];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        $plan = new BuilderObjectProductBuildPlan(); 
        $plan->setBuilderSource($this->objectSourceMapper->mapToObject($previouslyMappedObject[0]));
        foreach ($previouslyMappedObject[1] as $settingMethodData) {
            $plan->addSettingMethodCall($this->methodCallMapper->mapToObject($settingMethodData));
        }
        
        $plan->setBuildMethodCall($this->methodCallMapper->mapToObject($previouslyMappedObject[2]));
        return $plan;
    }
}
