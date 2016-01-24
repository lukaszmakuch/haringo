<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Mapper\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Mapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Mapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Mapper\ValueSourceArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;

/**
 * Builds array like this:
 * [String]
 * where the only value (with key index 0) is the held scalar value
 */
class ScalarValueMapper implements ValueSourceArrayMapper
{
    public function mapToArray(ValueSource $valueSource)
    {
        $this->throwExceptionIfUnsupported($valueSource);
        /* @var $valueSource ScalarValue */
        return [$valueSource->getHeldScalarValue()];
    }

    public function mapToObject(array $previouslyMappedArray)
    {
        $this->throwExceptionIfInvalidInputArray($previouslyMappedArray);
        return new ScalarValue($previouslyMappedArray[0]);
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
            || !is_string($previouslyMappedArray[0])
        ) {
            throw new ImpossibleToBuildFromArray();
        }
    }
}