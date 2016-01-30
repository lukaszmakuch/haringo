<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\FullClassPathSource\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Impl\ClassPathFromMap;

class ClassPathFromMapMapperTest extends ArrayMapperTest
{
    
    public function testCorrectMapping()
    {
        $mapper = new ClassPathFromMapMapper();
        
        $classSource = new ClassPathFromMap("abc");
        
        $sourceAsArray = $mapper->mapToArray($classSource);
        $rebuiltSource = $mapper->mapToObject($sourceAsArray);
        
        /* @var $rebuiltSource ClassPathFromMap */
        $this->assertInstanceOf(ClassPathFromMap::class, $rebuiltSource);
        $this->assertEquals("abc", $rebuiltSource->getKeyFromMap());
    }
    
}
