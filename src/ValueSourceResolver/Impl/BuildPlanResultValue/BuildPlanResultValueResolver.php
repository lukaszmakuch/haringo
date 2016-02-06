<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ValueSourceResolver\Impl\BuildPlanResultValue;

use lukaszmakuch\Haringo\BuildingStrategy\BuildingStrategy;
use lukaszmakuch\Haringo\Exception\UnableToBuild;
use lukaszmakuch\Haringo\ValueSource\Impl\BuildPlanResultValue;
use lukaszmakuch\Haringo\ValueSource\ValueSource;
use lukaszmakuch\Haringo\ValueSourceResolver\Exception\ImpossibleToResolveValue;
use lukaszmakuch\Haringo\ValueSourceResolver\ValueResolver;

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
        } catch (UnableToBuild $e) {
            throw new ImpossibleToResolveValue();
        }
    }
}

