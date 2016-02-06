<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuilderTestTpl;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\FactoryObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\TestClass;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanResultValue;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;

class TestFactoryClass
{
    public function getProduct($configValue)
    {
        return new TestClass($configValue);
    }
}

class FactoryObjectProductBuildPlanTest extends BuilderTestTpl
{
    public function testCorrectBuild()
    {
        $plan = new FactoryObjectProductBuildPlan();
        $plan->setFactoryObject(
            //build TestStaticFactory
            new BuildPlanResultValue((new NewInstanceBuildPlan())
                ->setClassSource(new ExactClassPath(TestFactoryClass::class)
            ))
        );
        $plan->setBuildMethodCall(
            (new MethodCall(new MethodByExactName("getProduct")))
                ->assignParamValue(new AssignedParamValue(
                    new ParamByExactName("configValue"),
                    new ScalarValue("paramValue")
                ))
        );
        
        /* @var $builtObject TestClass */
        $builtObject = $this->getRebuiltObjectBy($plan);
        $this->assertInstanceOf(TestClass::class, $builtObject);
        $this->assertEquals("paramValue", $builtObject->passedToConstructor);
    }
    
    public function testNoBuildMethod()
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = new FactoryObjectProductBuildPlan();
        $plan->setFactoryObject(
            //build TestStaticFactory
            new BuildPlanResultValue((new NewInstanceBuildPlan())
                ->setClassSource(new ExactClassPath(TestFactoryClass::class)
            ))
        );
        $this->builder->buildObjectBasedOn($plan);
    }
    
    public function testNoFactorySource()
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = new FactoryObjectProductBuildPlan();
        $this->builder->buildObjectBasedOn($plan);
    }
    
    public function testWrongFactorySource()
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = new FactoryObjectProductBuildPlan();
        $plan->setFactoryObject(new ScalarValue("not a factory"));
        $this->builder->buildObjectBasedOn($plan);
    }
}
