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
 * Maps exact class path to array and from array.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
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
