<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Mapper\BuildPlanArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\BuildPlanSerializer;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl\ArrayStringMapper\ArrayStringMapper;

class BuildPlanSerializerImpl implements BuildPlanSerializer
{
    private $buildPlanArrayMapper;
    private $arrayStringMapper;
    
    public function __construct(
        BuildPlanArrayMapper $planMapper,
        ArrayStringMapper $arrayStringMapper
    ) {
        $this->buildPlanArrayMapper = $planMapper;
        $this->arrayStringMapper = $arrayStringMapper;
    }
    
    public function deserialize($serializedPlan)
    {
        return $this->buildPlanArrayMapper->mapToObject(
            $this->arrayStringMapper->stringToArray($serializedPlan)
        );
    }

    public function serialize(BuildPlan $plan)
    {
        return $this->arrayStringMapper->arrayToString(
            $this->buildPlanArrayMapper->mapToArray($plan)
        );
    }

}