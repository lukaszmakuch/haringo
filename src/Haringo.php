<?php
/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo;

use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\Exception\BuildPlanNotFound;
use lukaszmakuch\Haringo\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\Haringo\Exception\UnableToSerialize;
use lukaszmakuch\Haringo\Exception\UnableToDeserialize;

/**
 * Builds objects based on given plans.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface Haringo
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
    
    /**
     * Translates the given build plan to a string.
     * 
     * @param BuildPlan $p
     * @throws UnableToSerialize
     */
    public function serializeBuildPlan(BuildPlan $p);
    
    /**
     * Translates the given string to a BuildPlan object.
     * 
     * @param String $serializedBuildPlan serialized BuildPlan (result of 
     * calling the serializeBuildPlan method)
     * 
     * @return BuildPlan
     * @throws UnableToDeserialize
     */
    public function deserializeBuildPlan($serializedBuildPlan);
}
