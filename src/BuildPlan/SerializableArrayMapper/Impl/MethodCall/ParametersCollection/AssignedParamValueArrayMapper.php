<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\Selector\ParamSelectorArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\ValueSource\ValueSourceArrayMapper;

/**
 * Uses structure like:
 * [array $mappedSelector, array $mappedValue]
 */
class AssignedParamValueArrayMapper
{
    private $paramSelectorArrayMapper;
    private $valueSourceArrayMapper;

    public function __construct(
        ParamSelectorArrayMapper $paramSelectorArrayMapper,
        ValueSourceArrayMapper $valueSourceArrayMapper
    ) {
        $this->paramSelectorArrayMapper = $paramSelectorArrayMapper;
        $this->valueSourceArrayMapper = $valueSourceArrayMapper;
    }
    
    public function mapToArray($objectToMap)
    {
        /* @var $assignedParamValue AssignedParamValue */
        $assignedParamValue = $objectToMap;
        return [
            $this->paramSelectorArrayMapper->mapToArray($assignedParamValue->getSelector()),
            $this->valueSourceArrayMapper->mapToArray($assignedParamValue->getValueSource()),
        ];
    }
    
    public function mapToObject(array $previouslyMappedObject)
    {
        return new AssignedParamValue(
            $this->paramSelectorArrayMapper->mapToObject($previouslyMappedObject[0]),
            $this->valueSourceArrayMapper->mapToObject($previouslyMappedObject[1])
        );
    }
}
