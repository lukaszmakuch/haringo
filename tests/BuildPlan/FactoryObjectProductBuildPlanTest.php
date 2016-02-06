<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\Impl;

use lukaszmakuch\Haringo\HaringoTestTpl;
use lukaszmakuch\Haringo\BuildPlan\Impl\FactoryObjectProductBuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;
use lukaszmakuch\Haringo\TestClass;
use lukaszmakuch\Haringo\ValueSource\Impl\BuildPlanResultValue;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;

class TestFactoryClass
{
    public function getProduct($configValue)
    {
        return new TestClass($configValue);
    }
}

class FactoryObjectProductBuildPlanTest extends HaringoTestTpl
{
    public function testCorrectBuild()
    {
        $plan = (new FactoryObjectProductBuildPlan())
            ->setFactoryObject(
                //build TestFactoryClass instance
                new BuildPlanResultValue((new NewInstanceBuildPlan())
                    ->setClassSource(new ExactClassPath(TestFactoryClass::class)
                ))
            )
            ->setBuildMethodCall(
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
        $this->haringo->buildObjectBasedOn($plan);
    }
    
    public function testNoFactorySource()
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = new FactoryObjectProductBuildPlan();
        $this->haringo->buildObjectBasedOn($plan);
    }
    
    public function testWrongFactorySource()
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = new FactoryObjectProductBuildPlan();
        $plan->setFactoryObject(new ScalarValue("not a factory"));
        $this->haringo->buildObjectBasedOn($plan);
    }
}
