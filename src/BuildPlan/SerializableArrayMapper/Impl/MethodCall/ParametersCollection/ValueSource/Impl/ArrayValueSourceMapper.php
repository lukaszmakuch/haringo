<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\ValueSource\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Impl\ArrayValueSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\ValueSource\ValueSourceArrayMapper;

class ArrayValueSourceMapper implements ValueSourceArrayMapper
{
    private $actualMapper;
    
    public function __construct(ValueSourceArrayMapper $actualMapper)
    {
        $this->actualMapper = $actualMapper;
    }
    
    public function mapToArray($objectToMap)
    {
        /* @var $objectToMap ArrayValueSource */
        $result = [];
        foreach ($objectToMap->getAllSources() as $key => $valueSource) {
            $result[$key] = $this->actualMapper->mapToArray($valueSource);
        }
        
        return $result;
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        $arrayValueSource = new ArrayValueSource();
        foreach ($previouslyMappedObject as $index => $mappedValueSource) {
            $arrayValueSource->addValue(
                $index, 
                $this->actualMapper->mapToObject($mappedValueSource)
            );
        }
        
        return $arrayValueSource;
    }
}
