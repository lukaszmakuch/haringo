<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelectorMapper\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\MethodSelectorFromMap;
use lukaszmakuch\ObjectBuilder\MethodSelectorMapper\MethodSelectorArrayMapper;

/**
 * Maps method selectors from map to array.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MethodSelectorFromMapMapper implements MethodSelectorArrayMapper
{
    private static $MAPPED_INDEX_KEY_FROM_MAP = 0;
    public function mapToArray($objectToMap)
    {
        /* @var $selector MethodSelectorFromMap */
        $selector = $objectToMap;
        return [$selector->getKeyFromMap()];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        return new MethodSelectorFromMap($previouslyMappedObject[self::$MAPPED_INDEX_KEY_FROM_MAP]);
    }
}
