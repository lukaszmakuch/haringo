<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodCallMapper\ParametersCollection\Selector\Impl;

use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\MethodCallMapper\ParametersCollection\Selector\ParamSelectorArrayMapper;

/*
 * It uses following format of array:
 * [String] where the only String with index 0 is the parameter name
 */
class ParamByExactNameMapperImpl implements ParamSelectorArrayMapper
{
    public function mapToArray($objectToMap)
    {
        /* @var $selector ParamByExactName */
        $selector = $objectToMap;
        $this->throwExceptionIfUnsupported($selector);
        return [$selector->getExactName()];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        $this->throwExceptionIfIncorrectInputArray($previouslyMappedObject);
        return new ParamByExactName($previouslyMappedObject[0]);
    }

    private function throwExceptionIfUnsupported(ParameterSelector $selector)
    {
        if (false === ($selector instanceof ParamByExactName)) {
            throw new ImpossibleToMapObject(sprintf(
                "trying to use %s to serialize %s",
                __CLASS__,
                get_class($selector)    
            ));
        }
    }
    
    private function throwExceptionIfIncorrectInputArray(array $previouslyMappedArray)
    {
        if (
            empty($previouslyMappedArray)
            || !isset($previouslyMappedArray[0])
            || !is_string($previouslyMappedArray[0])
        ) {
            throw new ImpossibleToBuildFromArray(sprintf(
                "%s is not able to map %s into object, required structure is: %s",
                __CLASS__,
                print_r($previouslyMappedArray, true),
                "[String]"
            ));
        }
    }
}
