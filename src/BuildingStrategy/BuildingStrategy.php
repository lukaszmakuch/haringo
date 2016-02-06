<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildingStrategy;

use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\Exception\ImpossibleToFinishBuildPlan;

/**
 * Allows to build some object based on a BuildPlan.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface BuildingStrategy
{
    /**
     * Build an object based on the given plan.
     * 
     * @return mixed object built based on the given BuildPlan
     * @throws ImpossibleToFinishBuildPlan
     */
    public function buildObjectBasedOn(BuildPlan $p);
}
