<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodCallMapper\ParametersCollection\ValueSource\Impl;

use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\MethodCallMapper\ParametersCollection\ValueSource\ValueSourceArrayMapper;

/**
 * Builds array like this:
 * [String]
 * where the only value (with key index 0) is the held scalar value
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
    
    protected function throwExceptionIfUnsupported(ValueSource $valueSource)
    {
        if (false === ($valueSource instanceof ScalarValue)) {
            throw new ImpossibleToMapObject();
        }
    }
    
    protected function throwExceptionIfInvalidInputArray(array $previouslyMappedArray)
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