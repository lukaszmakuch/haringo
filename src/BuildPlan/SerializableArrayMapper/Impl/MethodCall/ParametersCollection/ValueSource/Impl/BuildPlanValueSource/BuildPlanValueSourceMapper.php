<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\ValueSource\Impl\BuildPlanValueSource;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Impl\BuildPlanValueSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\BuildPlanArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\ValueSource\ValueSourceArrayMapper;

class BuildPlanValueSourceMapper  implements ValueSourceArrayMapper
{
    private $buildPlanMapper;
    
    public function __construct(BuildPlanArrayMapper $buildPlanMapper)
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
