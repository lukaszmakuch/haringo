<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Serializer;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;

interface BuildPlanSerializer
{
    /**
     * @param BuildPlan $plan plan to serialze
     * 
     * @return String
     */
    public function serialize(BuildPlan $plan);
    
    public function deserialize($serializedPlan);
}
