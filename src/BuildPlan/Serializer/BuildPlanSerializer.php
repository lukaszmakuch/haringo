<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Serializer;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Exception\UnableToDeserialize;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Exception\UnableToSerialize;

/**
 * Supports serialization and deserialization of BuildPlan objects.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface BuildPlanSerializer
{
    /**
     * @param BuildPlan $plan plan to serialze
     * 
     * @return String
     * @throws UnableToSerialize
     */
    public function serialize(BuildPlan $plan);
    
    /**
     * @param String $serializedPlan previously serialized build plan
     * 
     * @return BuildPlan
     * @throws UnableToDeserialize
     */
    public function deserialize($serializedPlan);
}
