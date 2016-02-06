<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan;

use lukaszmakuch\ObjectBuilder\BuilderTestTpl;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\BuilderObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\TestClass;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanResultValue;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;

class TestBuilder
{
    private $param;
    public function setConstructorParam($param) { $this->param = $param; }
    public function build()
    {
        return new TestClass($this->param);
    }
}

class BuilderProductBuildPlanTest extends BuilderTestTpl
{
    public function testCorrectBuild()
    {
        $plan = new BuilderObjectProductBuildPlan();
        $plan->setBuilderSource(
            //build TestBuilder object
            new BuildPlanResultValue((new NewInstanceBuildPlan())
                ->setClassSource(new ExactClassPath(TestBuilder::class)
            ))
        );
        $plan->addSettingMethodCall(
            (new MethodCall(new ExactMethodName("setConstructorParam")))
                ->assignParamValue(new AssignedParamValue(
                    new ParamByExactName("param"),
                    new ScalarValue("paramValue")
                ))
        );
        $plan->setBuildMethodCall(
            (new MethodCall(new ExactMethodName("build")))
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
        $this->builder->buildObjectBasedOn($plan);
    }
    
    public function testExceptionWhenNoBuilderSource()
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = new BuilderObjectProductBuildPlan();
        $this->builder->buildObjectBasedOn($plan);
    }
    
    public function testExceptionWhenBuilderIsNotObject()
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = new BuilderObjectProductBuildPlan();
        $plan->setBuilderSource(new ScalarValue("not a builder for sure"));
        $plan->setBuildMethodCall(
            (new MethodCall(new ExactMethodName("build")))
        );
        $this->builder->buildObjectBasedOn($plan);
    }
}
