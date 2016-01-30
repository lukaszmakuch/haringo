<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodCallMapper\ParametersCollection\Selector\Impl;

use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\ObjectBuilder\MethodCallMapper\ParametersCollection\Selector\ParamSelectorArrayMapper;

class ParamByPositionMapper implements ParamSelectorArrayMapper
{
    public function mapToArray($objectToMap)
    {
        /* @var $objectToMap ParamByPosition */
        return [$objectToMap->getParamPosFrom0()];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        $mappedPos = $previouslyMappedObject[0];
        return new ParamByPosition($mappedPos);
    }
}