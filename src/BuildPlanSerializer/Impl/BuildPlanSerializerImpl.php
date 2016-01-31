<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Impl;

use lukaszmakuch\ObjectBuilder\ArrayStringMapper\ArrayStringMapper;
use lukaszmakuch\ObjectBuilder\ArrayStringMapper\Exception\UnableToMapToArray;
use lukaszmakuch\ObjectBuilder\ArrayStringMapper\Exception\UnableToMapToString;
use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlanMapper\BuildPlanMapper;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\BuildPlanSerializer;
use lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Exception\UnableToSerialize;
use lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToMapObject;

class BuildPlanSerializerImpl implements BuildPlanSerializer
{
    private $buildPlanArrayMapper;
    private $arrayStringMapper;
    
    public function __construct(
        BuildPlanMapper $planMapper,
        ArrayStringMapper $arrayStringMapper
    ) {
        $this->buildPlanArrayMapper = $planMapper;
        $this->arrayStringMapper = $arrayStringMapper;
    }
    
    public function deserialize($serializedPlan)
    {
        try {
            return $this->deserializeImpl($serializedPlan);
        } catch (UnableToMapToArray $e) {
            throw new UnableToSerialize();
        } catch (ImpossibleToBuildFromArray $e) {
            throw new UnableToSerialize();
        }
    }

    public function serialize(BuildPlan $plan)
    {
        try {
            return $this->serializeImpl($plan);
        } catch (UnableToMapToString $e) {
            throw new UnableToSerialize();
        } catch (ImpossibleToMapObject $e) {
            throw new UnableToSerialize();
        }
    }
    
    /**
     * @param String $serializedPlan
     * @return BuildPlan
     * @throws UnableToMapToArray
     * @throws ImpossibleToBuildFromArray
     */
    private function deserializeImpl($serializedPlan)
    {
        return $this->buildPlanArrayMapper->mapToObject(
            $this->arrayStringMapper->stringToArray($serializedPlan)
        );
    }
    
    /**
     * @return String
     * @throws UnableToMapToString
     * @throws ImpossibleToMapObject
     */
    private function serializeImpl(BuildPlan $plan)
    {
        return $this->arrayStringMapper->arrayToString(
            $this->buildPlanArrayMapper->mapToArray($plan)
        );
    }
}
