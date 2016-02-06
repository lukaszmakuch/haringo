<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ClassSource;

use lukaszmakuch\Haringo\HaringoTestTpl;
use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ClassPathFromMap;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\Haringo\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\Haringo\TestClass as TestClass;

class ClassSourceTest extends HaringoTestTpl
{
    protected function getClassSourceMap()
    {
        $map = new ClassPathSourceMap();
        $map->addSource("class_from_map", new ExactClassPath(TestClass::class));
        return $map;
    }

    public function testExactNameSource()
    {
        $this->checkClassSource(new ExactClassPath(TestClass::class));
    }
    
    public function testWrongExactNameSource()
    {
        $this->assertExceptionWhenWrongSource(new ExactClassPath("NoClassLikeThat"));
    }

    public function testClassSourceFromMap()
    {
        $this->checkClassSource(new ClassPathFromMap("class_from_map"));
    }
    
    public function testWongKeyFromMap()
    {
        $this->assertExceptionWhenWrongSource(new ClassPathFromMap("wrong_key"));
    }

    protected function checkClassSource(FullClassPathSource $source)
    {
        $plan = new NewInstanceBuildPlan();
        $plan->setClassSource($source);
        
        $builtObject = $this->getRebuiltObjectBy($plan);
        $this->assertInstanceOf(TestClass::class, $builtObject);
    }
    
    protected function assertExceptionWhenWrongSource(FullClassPathSource $source)
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = new NewInstanceBuildPlan();
        $plan->setClassSource($source);
        $this->getRebuiltObjectBy($plan);
    }
}
