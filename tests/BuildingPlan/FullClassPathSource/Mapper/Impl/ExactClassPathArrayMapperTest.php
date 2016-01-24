<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Mapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Impl\ExactClassPath;

class ExactClassPathArrayMapperTest extends ArrayMapperTest
{
    
    public function testCorrectMapping()
    {
        $mapper = new ExactClassPathArrayMapper();
        
        $classSourceMapper = new ExactClassPath("abc");
        
        $sourceAsArray = $mapper->mapToArray($classSourceMapper);
        $rebuiltSource = $mapper->mapToObject($sourceAsArray);
        
        /* @var $rebuiltSource ExactClassPath */
        $this->assertInstanceOf(ExactClassPath::class, $rebuiltSource);
        $this->assertEquals("abc", $rebuiltSource->getHeldFullClassPath());
    }
    
}