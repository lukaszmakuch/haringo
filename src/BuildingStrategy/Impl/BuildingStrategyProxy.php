<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildingStrategy\Impl;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;
use lukaszmakuch\Haringo\BuildingStrategy\BuildingStrategy;
use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\Exception\UnableToBuild;

/**
 * Proxy that allows to assign some building strategy to target build plan class.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuildingStrategyProxy implements BuildingStrategy
{
    private $strategyByBuildPlan;
    
    public function __construct()
    {
        $this->strategyByBuildPlan = new ClassBasedRegistry();
    }
   
    /**
     * Adds a building strategy to the proxy.
     * 
     * @param BuildingStrategy $actualStrategy strategy to use
     * @param String $targetBuildPlanClass BuildPlan class that should be 
     * supproted by this strategy
     */
    public function registerStrategy(
        BuildingStrategy $actualStrategy,
        $targetBuildPlanClass
    ) {
        $this->strategyByBuildPlan->associateValueWithClasses(
            $actualStrategy,
            [$targetBuildPlanClass]
        );
    }
    
    public function buildObjectBasedOn(BuildPlan $p)
    {
        $actualStrategy = $this->getActualStrategyBy($p);
        return $actualStrategy->buildObjectBasedOn($p);
    }
    
    /**
     * @param BuildPlan $p
     * @return BuildingStrategy
     * 
     * @throw UnableToBuild
     */
    private function getActualStrategyBy(BuildPlan $p)
    {
        try {
            return $this->strategyByBuildPlan->fetchValueByObjects([$p]);
        } catch (ValueNotFound $e) {
            throw new UnableToBuild();
        }
    }
}
