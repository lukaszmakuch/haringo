<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl;

use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\ParamSelectorArrayMapper;

/**
 * Maps param by position selectors.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
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
