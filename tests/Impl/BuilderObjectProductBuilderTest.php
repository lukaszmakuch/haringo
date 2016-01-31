<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\BuilderObjectProductBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\Impl\BuilderTestTpl;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\TestClass;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanValueSource;
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

class BuilderObjectProductBuilderTest extends BuilderTestTpl
{
    public function testBuildingFromDeserializedPlan()
    {
        $plan = new BuilderObjectProductBuildPlan();
        $plan->setBuilderSource(
            //build TestBuilder object
            new BuildPlanValueSource((new NewInstanceBuildPlan())
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
}
