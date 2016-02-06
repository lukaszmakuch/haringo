<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildPlan;

use lukaszmakuch\Haringo\HaringoTestTpl;
use lukaszmakuch\Haringo\BuildPlan\Impl\BuilderObjectProductBuildPlan;
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

class TestBuilder
{
    private $param;
    public function setConstructorParam($param) { $this->param = $param; }
    public function build()
    {
        return new TestClass($this->param);
    }
}

class BuilderProductBuildPlanTest extends HaringoTestTpl
{
    public function testCorrectBuild()
    {
        $plan = (new BuilderObjectProductBuildPlan())
            ->setBuilderSource(
                //build TestBuilder object
                new BuildPlanResultValue((new NewInstanceBuildPlan())
                    ->setClassSource(new ExactClassPath(TestBuilder::class)
                ))
            )
            ->addSettingMethodCall(
                (new MethodCall(new MethodByExactName("setConstructorParam")))
                    ->assignParamValue(new AssignedParamValue(
                        new ParamByExactName("param"),
                        new ScalarValue("paramValue")
                    ))
            )
            ->setBuildMethodCall(
                (new MethodCall(new MethodByExactName("build")))
            );

        /* @var $builtObject TestClass */
        $builtObject = $this->getRebuiltObjectBy($plan);
        $this->assertInstanceOf(TestClass::class, $builtObject);
        $this->assertEquals("paramValue", $builtObject->passedToConstructor);
    }
    
    public function testExceptionWhenNoBuildMethodSet()
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = new BuilderObjectProductBuildPlan();
        $plan->setBuilderSource(
            //build TestBuilder object
            new BuildPlanResultValue((new NewInstanceBuildPlan())
                ->setClassSource(new ExactClassPath(TestBuilder::class)
            ))
        );
        $this->haringo->buildObjectBasedOn($plan);
    }
    
    public function testExceptionWhenNoBuilderSource()
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = new BuilderObjectProductBuildPlan();
        $this->haringo->buildObjectBasedOn($plan);
    }
    
    public function testExceptionWhenBuilderIsNotObject()
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = new BuilderObjectProductBuildPlan();
        $plan->setBuilderSource(new ScalarValue("not a builder for sure"));
        $plan->setBuildMethodCall(
            (new MethodCall(new MethodByExactName("build")))
        );
        $this->haringo->buildObjectBasedOn($plan);
    }
}
