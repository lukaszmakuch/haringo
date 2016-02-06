<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildPlanSerializer\Impl;

use lukaszmakuch\Haringo\ArrayStringMapper\ArrayStringMapper;
use lukaszmakuch\Haringo\ArrayStringMapper\Exception\UnableToMapToArray;
use lukaszmakuch\Haringo\ArrayStringMapper\Exception\UnableToMapToString;
use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\BuildPlanSerializer\BuildPlanSerializer;
use lukaszmakuch\Haringo\BuildPlanSerializer\Exception\UnableToSerialize;
use lukaszmakuch\Haringo\Mapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\Haringo\Mapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\Haringo\Mapper\SerializableArrayMapper;

/**
 * Default implementation of the serializer.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuildPlanSerializerImpl implements BuildPlanSerializer
{
    private $buildPlanArrayMapper;
    private $arrayStringMapper;
    
    /**
     * Provides dependencies.
     * 
     * @param SerializableArrayMapper $planMapper used to map a build plan 
     * to an array
     * @param ArrayStringMapper $arrayStringMapper used to map the array
     * to a string
     */
    public function __construct(
        SerializableArrayMapper $planMapper,
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
