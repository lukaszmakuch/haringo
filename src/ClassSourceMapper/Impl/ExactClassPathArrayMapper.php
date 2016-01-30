<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ClassSourceMapper\Impl;

use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\ClassSourceMapper\ClassSourceMapper;

/**
 * Uses structure like:
 * [String]
 * where the string with index 0 is the exact class path
 */
class ExactClassPathArrayMapper implements ClassSourceMapper
{
    public function mapToArray($objectToMap)
    {
        /* @var $classSource ExactClassPath */
        $classSource = $objectToMap;
        return [$classSource->getHeldFullClassPath()];
    }
    
    public function mapToObject(array $previouslyMappedObject)
    {
        return new ExactClassPath($previouslyMappedObject[0]);
    }
}
