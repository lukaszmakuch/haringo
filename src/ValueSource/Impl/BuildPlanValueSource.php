<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSource\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

class BuildPlanValueSource implements ValueSource
{
    private $heldBuildPlan;
    
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
