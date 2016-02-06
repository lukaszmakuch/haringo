<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ValueSourceMapper\Impl;

use lukaszmakuch\Haringo\Mapper\SerializableArrayMapper;
use lukaszmakuch\Haringo\ValueSource\Impl\ArrayValue;
use lukaszmakuch\Haringo\ValueSourceMapper\ValueSourceArrayMapper;

/**
 * Maps array value source to array.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ArrayValueMapper implements ValueSourceArrayMapper
{
    private $actualMapper;
    
    /**
     * Provides the actual mapper used to map values that are held within
     * the array value source.
     * 
     * @param SerializableArrayMapper $actualMapper
     */
    public function __construct(SerializableArrayMapper $actualMapper)
    {
        $this->actualMapper = $actualMapper;
    }
    
    public function mapToArray($objectToMap)
    {
        /* @var $objectToMap ArrayValue */
        $result = [];
        foreach ($objectToMap->getAllSources() as $key => $valueSource) {
            $result[$key] = $this->actualMapper->mapToArray($valueSource);
        }
        
        return $result;
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        $ArrayValue = new ArrayValue();
        foreach ($previouslyMappedObject as $index => $mappedValueSource) {
            $ArrayValue->addValue(
                $index, 
                $this->actualMapper->mapToObject($mappedValueSource)
            );
        }
        
        return $ArrayValue;
    }
}
