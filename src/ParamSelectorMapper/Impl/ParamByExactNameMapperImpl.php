<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ParamSelectorMapper\Impl;

use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamSelector\ParameterSelector;
use lukaszmakuch\Haringo\Mapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\Haringo\Mapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\Haringo\ParamSelectorMapper\ParamSelectorArrayMapper;

/**
 * Maps param by exact name selectors.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
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

    /**
     * @param ParameterSelector $selector
     * @throws ImpossibleToMapObject when this selector is not supported
     */
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
    
    /**
     * @param array $previouslyMappedArray
     * @throws ImpossibleToBuildFromArray
     */
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
