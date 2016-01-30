<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;

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
