<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ClassSource;

use lukaszmakuch\ObjectBuilder\BuilderTestTpl;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ClassPathFromMap;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\ObjectBuilder\TestClass as TestClass;

class ClassSourceTest extends BuilderTestTpl
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
