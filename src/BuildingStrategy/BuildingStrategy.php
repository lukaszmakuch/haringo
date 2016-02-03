<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingStrategy;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;

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
