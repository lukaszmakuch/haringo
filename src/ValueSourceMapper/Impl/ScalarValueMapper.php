<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl;

use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\Mapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\ValueSourceArrayMapper;

/**
 * Maps scalar value source to array and from array.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ScalarValueMapper implements ValueSourceArrayMapper
{
    public function mapToArray($objectToMap)
    {
        /* @var $valueSource ScalarValue */
        $valueSource = $objectToMap;
        $this->throwExceptionIfUnsupported($valueSource);
        return [$valueSource->getHeldScalarValue()];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        $this->throwExceptionIfInvalidInputArray($previouslyMappedObject);
        return new ScalarValue($previouslyMappedObject[0]);
    }
    
    /**
     * @param ValueSource $valueSource
     * @throws ImpossibleToMapObject
     */
    private function throwExceptionIfUnsupported(ValueSource $valueSource)
    {
        if (false === ($valueSource instanceof ScalarValue)) {
            throw new ImpossibleToMapObject();
        }
    }

    /**
     * @param array $previouslyMappedArray
     * @throws ImpossibleToBuildFromArray
     */
    private function throwExceptionIfInvalidInputArray(array $previouslyMappedArray)
    {
        if (
            empty($previouslyMappedArray)
            || !isset($previouslyMappedArray[0])
            || !is_scalar($previouslyMappedArray[0])
        ) {
            throw new ImpossibleToBuildFromArray();
        }
    }
}
