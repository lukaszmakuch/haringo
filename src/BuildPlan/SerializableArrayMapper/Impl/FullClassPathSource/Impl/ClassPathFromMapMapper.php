<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\FullClassPathSource\Impl;

use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ClassPathFromMap;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\FullClassPathSource\ClassSourceMapper;

class ClassPathFromMapMapper implements ClassSourceMapper
{
    private static $MAPPED_INDEX_KEY_FROM_MAP = 0;
    
    public function mapToArray($objectToMap)
    {
        /* @var $objectToMap ClassPathFromMap */
        return [$objectToMap->getKeyFromMap()];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        $keyFromMap = $previouslyMappedObject[self::$MAPPED_INDEX_KEY_FROM_MAP];
        return new ClassPathFromMap($keyFromMap);
    }
}
