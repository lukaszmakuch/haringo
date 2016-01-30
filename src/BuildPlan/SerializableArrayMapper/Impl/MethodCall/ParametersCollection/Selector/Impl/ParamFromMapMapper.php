<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\Selector\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Impl\ParamFromMap;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\Selector\ParamSelectorArrayMapper;

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
