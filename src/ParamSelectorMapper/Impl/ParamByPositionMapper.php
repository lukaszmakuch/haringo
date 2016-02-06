<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ParamSelectorMapper\Impl;

use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\Haringo\ParamSelectorMapper\ParamSelectorArrayMapper;

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
