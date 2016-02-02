<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamValueMapper;

use lukaszmakuch\ObjectBuilder\Mapper\SerializableArrayMapper;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;

/**
 * Maps assigned parameter values with selectors to array.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class AssignedParamValueArrayMapper implements SerializableArrayMapper
{
    private $paramSelectorArrayMapper;
    private $valueSourceArrayMapper;

    /**
     * Provides dependencies.
     * 
     * @param SerializableArrayMapper $paramSelectorArrayMapper used to map selectors
     * @param SerializableArrayMapper $valueSourceArrayMapper used to map value sources
     */
    public function __construct(
        SerializableArrayMapper $paramSelectorArrayMapper,
        SerializableArrayMapper $valueSourceArrayMapper
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
