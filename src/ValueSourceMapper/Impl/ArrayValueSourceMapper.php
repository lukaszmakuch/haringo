<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl;

use lukaszmakuch\ObjectBuilder\Mapper\SerializableArrayMapper;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ArrayValueSource;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\ValueSourceArrayMapper;

/**
 * Maps array value source to array.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ArrayValueSourceMapper implements ValueSourceArrayMapper
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
