<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Mapper\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Mapper\ClassSourceMapper;

/**
 * Uses structure like:
 * [String]
 * where the string with index 0 is the exact class path
 */
class ExactClassPathArrayMapper implements ClassSourceMapper
{
    public function mapToArray(FullClassPathSource $classSource)
    {
        /* @var $classSource ExactClassPath */
        return [$classSource->getHeldFullClassPath()];
    }
    
    public function mapToObject(array $previouslyMappedArray)
    {
        return new ExactClassPath($previouslyMappedArray[0]);
    }
}
