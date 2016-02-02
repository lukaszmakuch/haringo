<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\BuildPlanValueSource;

use lukaszmakuch\ObjectBuilder\Mapper\SerializableArrayMapper;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanValueSource;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\ValueSourceArrayMapper;

/**
 * Maps a value source based on build plan to and from array.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuildPlanValueSourceMapper  implements ValueSourceArrayMapper
{
    private $buildPlanMapper;
    
    /**
     * Provides dependencies.
     * 
     * @param SerializableArrayMapper $buildPlanMapper used to map build plans to
     * arrays and from arrays to build plans.
     */
    public function __construct(SerializableArrayMapper $buildPlanMapper)
    {
        $this->buildPlanMapper = $buildPlanMapper;
    }
    
    public function mapToArray($objectToMap)
    {
        /* @var $objectToMap BuildPlanValueSource */
        return [$this->buildPlanMapper->mapToArray($objectToMap->getBuildPlan())];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        $storedBuild = $this->buildPlanMapper->mapToObject($previouslyMappedObject[0]);
        return new BuildPlanValueSource($storedBuild);
    }
}
