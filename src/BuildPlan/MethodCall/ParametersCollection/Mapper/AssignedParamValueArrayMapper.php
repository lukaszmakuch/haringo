<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Mapper;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ArrayMapper\ParamSelectorArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Mapper\ValueSourceArrayMapper;

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
    
    public function mapToArray(AssignedParamValue $assignedParamValue)
    {
        return [
            $this->paramSelectorArrayMapper->mapToArray($assignedParamValue->getSelector()),
            $this->valueSourceArrayMapper->mapToArray($assignedParamValue->getValueSource()),
        ];
    }
    
    /**
     * 
     * @param array $previouslyMappedArray array previously received as 
     * the result of the mapToArray method.
     * 
     * @return AssignedParamValue
     */
    public function mapToObject(array $previouslyMappedArray)
    {
        return new AssignedParamValue(
            $this->paramSelectorArrayMapper->mapToObject($previouslyMappedArray[0]),
            $this->valueSourceArrayMapper->mapToObject($previouslyMappedArray[1])
        );
    }
}
