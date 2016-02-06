<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ValueSource\Impl;

use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\ValueSource\ValueSource;

/**
 * Represents a value source that is actually a whole different BuildPlan.
 * 
 * Allows to build complex structures by composition.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuildPlanResultValue implements ValueSource
{
    private $heldBuildPlan;
    
    /**
     * Sets the build plan that should be used in order to resolve some value.
     * @param BuildPlan $planToUse
     */
    public function __construct(BuildPlan $planToUse)
    {
        $this->heldBuildPlan = $planToUse;
    }
    
    /**
     * @return BuildPlan
     */
    public function getBuildPlan()
    {
        return $this->heldBuildPlan;
    }
}
