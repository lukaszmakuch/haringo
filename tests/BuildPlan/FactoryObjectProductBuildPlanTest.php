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
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\TestClass;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanValueSource;
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
            new BuildPlanValueSource((new NewInstanceBuildPlan())
                ->setClassSource(new ExactClassPath(TestFactoryClass::class)
            ))
        );
        $plan->setBuildMethodCall(
            (new MethodCall(new ExactMethodName("getProduct")))
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
}
