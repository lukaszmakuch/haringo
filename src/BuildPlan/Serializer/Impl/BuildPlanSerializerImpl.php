<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\BuildPlanArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\BuildPlanSerializer;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Exception\UnableToSerialize;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl\ArrayStringMapper\ArrayStringMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl\ArrayStringMapper\Exception\UnableToMapToString;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl\ArrayStringMapper\Exception\UnableToMapToArray;

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
