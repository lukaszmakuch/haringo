<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ClassSourceMapper\Impl;

use lukaszmakuch\Haringo\ClassSource\Impl\ClassPathFromMap;
use lukaszmakuch\Haringo\ClassSourceMapper\ClassSourceMapper;

/**
 * Maps a class path from map to an array and from array to object.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
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
