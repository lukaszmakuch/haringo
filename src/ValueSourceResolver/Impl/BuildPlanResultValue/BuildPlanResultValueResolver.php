<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\BuildPlanResultValue;

use lukaszmakuch\ObjectBuilder\BuildingStrategy\BuildingStrategy;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanResultValue;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Exception\ImpossibleToResolveValue;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\ValueResolver;

/**
 * Resolves values based on build plans.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuildPlanResultValueResolver  implements ValueResolver
{
    private $buildingStrategy;
    
    /**
     * Provides building strategy used to build objects 
     * based on given value sources.
     * 
     * @param BuildingStrategy $buildingStrategy
     */
    public function __construct(BuildingStrategy $buildingStrategy)
    {
        $this->buildingStrategy = $buildingStrategy;
    }

    public function resolveValueFrom(ValueSource $source)
    {
        try {
            /* @var $source BuildPlanResultValue */
            return $this->buildingStrategy->buildObjectBasedOn($source->getBuildPlan());
        } catch (ImpossibleToFinishBuildPlan $e) {
            throw new ImpossibleToResolveValue();
        }
    }
}

