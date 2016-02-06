<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\MethodSelectorMapper\Impl;

use lukaszmakuch\Haringo\MethodSelector\Impl\MethodFromMap;
use lukaszmakuch\Haringo\MethodSelectorMapper\MethodSelectorArrayMapper;

/**
 * Maps method selectors from map to array.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MethodFromMapMapper implements MethodSelectorArrayMapper
{
    private static $MAPPED_INDEX_KEY_FROM_MAP = 0;
    public function mapToArray($objectToMap)
    {
        /* @var $selector MethodFromMap */
        $selector = $objectToMap;
        return [$selector->getKeyFromMap()];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        return new MethodFromMap($previouslyMappedObject[self::$MAPPED_INDEX_KEY_FROM_MAP]);
    }
}
