<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ArrayMapper\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ArrayMapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ArrayMapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ArrayMapper\ParamSelectorArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ParameterSelector;

/*
 * It uses following format of array:
 * [String] where the only String with index 0 is the parameter name
 */
class ParamByExactNameMapperImpl implements ParamSelectorArrayMapper
{
    public function mapToArray(ParameterSelector $selector)
    {
        $this->throwExceptionIfUnsupported($selector);
        /* @var $selector ParamByExactName */
        return [$selector->getExactName()];
    }

    public function mapToObject(array $previouslyMappedArray)
    {
        $this->throwExceptionIfIncorrectInputArray($previouslyMappedArray);
        return new ParamByExactName($previouslyMappedArray[0]);
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