<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildingStrategy\BuildingStrategy;
use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\BuildPlanSerializer;
use lukaszmakuch\ObjectBuilder\Exception\BuildPlanNotFound;
use lukaszmakuch\ObjectBuilder\ObjectBuilder;
use SplObjectStorage;

/**
 * Default implementation of ObjectBuilder.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ObjectBuilderImpl implements ObjectBuilder
{
    private $serializer;
    
    private $buildingStrategy;
    
    private $buildPlanByBuildObject;
    
    /**
     * Provides dependencies.
     * 
     * @param BuildPlanSerializer $serializer used to transform BuildPlan
     * objects to string and then from strings back to objects.
     * @param BuildingStrategy $buildingStrategy used to actually get an object
     * based on some BuildPlan.
     */
    public function __construct(
        BuildPlanSerializer $serializer,
        BuildingStrategy $buildingStrategy
    ) {
        $this->serializer = $serializer;
        $this->buildingStrategy = $buildingStrategy;
        $this->buildPlanByBuildObject = new SplObjectStorage();
    }
    
    public function buildObjectBasedOn(BuildPlan $p)
    {
        $builtObject = $this->buildingStrategy->buildObjectBasedOn($p);
        $this->rememberBuildPlanOfObject($p, $builtObject);
        return $builtObject;
    }

    public function deserializeBuildPlan($serializedBuildPlan)
    {
        return $this->serializer->deserialize($serializedBuildPlan);
    }

    public function getBuildPlanUsedToBuild($previouslyBuiltObject)
    {
        $this->throwExceptionIfNoBuildPlanFor($previouslyBuiltObject);
        $foundObject = $this->buildPlanByBuildObject->offsetGet($previouslyBuiltObject);
        return $foundObject;
    }

    public function serializeBuildPlan(BuildPlan $p)
    {
        return $this->serializer->serialize($p);
    }
    
    /**
     * @param BuildPlan $p
     * @param mixed $builtObject
     * @return null
     */
    private function rememberBuildPlanOfObject(BuildPlan $p, $builtObject)
    {
        $this->buildPlanByBuildObject->attach($builtObject, $p);
    }
    
    /**
     * Throws exception if this object hasn't been built by this builder.
     * 
     * @param mixed $previouslyBuiltObject
     * @throws BuildPlanNotFound
     */
    private function throwExceptionIfNoBuildPlanFor($previouslyBuiltObject)
    {
        if (!$this->buildPlanByBuildObject->offsetExists($previouslyBuiltObject)) {
            throw new BuildPlanNotFound("build plan for object not found");
        }
    }
}
