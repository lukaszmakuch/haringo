<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\Selector\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Impl\MethodSelectorFromMap;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\Selector\MethodSelectorArrayMapper;

/**
 * Uses array formated like that:
 * [String]
 * where the only String value (with index 0) is the key value from the map.
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
