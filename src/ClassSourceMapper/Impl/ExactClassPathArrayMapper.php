<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ClassSourceMapper\Impl;

use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\ClassSourceMapper\ClassSourceMapper;

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
