<?php
/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\Exception\BuildPlanNotFound;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;

/**
 * Builds objects based on given plans.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ObjectBuilder
{
    /**
     * Build an object based on the given plan.
     * 
     * @return mixed object built based on the given BuildPlan
     * @throws ImpossibleToFinishBuildPlan
     */
    public function buildObjectBasedOn(BuildPlan $p);
    
    /**
     * Gets build plan used to build the given object. 
     * 
     * @param mixed $previouslyBuiltObject any object that has been 
     * previously built by this builder
     * 
     * @return BuildPlan
     * @throws BuildPlanNotFound
     */
    public function getBuildPlanUsedToBuild($previouslyBuiltObject);
}
