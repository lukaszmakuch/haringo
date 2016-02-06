<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ParamSelectorMapper\Impl;

use lukaszmakuch\Haringo\ParamSelector\Impl\ParamFromMap;
use lukaszmakuch\Haringo\ParamSelectorMapper\ParamSelectorArrayMapper;

/**
 * Maps param from map selectors.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ParamFromMapMapper implements ParamSelectorArrayMapper
{
    private static $MAPPED_INDEX_KEY_FROM_MAP = 0;
    
    public function mapToArray($objectToMap)
    {
        /* @var $objectToMap ParamFromMap */
        return [$objectToMap->getKeyFromMap()];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        $key = $previouslyMappedObject[self::$MAPPED_INDEX_KEY_FROM_MAP];
        return new ParamFromMap($key);
    }
}
