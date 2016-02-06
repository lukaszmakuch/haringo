<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan;

use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\BuilderTestTpl;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\TestClass;

class NewInstanceBuildPlanTest extends BuilderTestTpl
{
    public function testCorrectBuild()
    {
        $plan = (new NewInstanceBuildPlan())
            ->setClassSource(new ExactClassPath(TestClass::class))
            ->addMethodCall(
                (new MethodCall(new MethodByExactName("setMembers")))
                    ->assignParamValue(new AssignedParamValue(
                        new ParamByExactName("newB"), 
                        new ScalarValue("secondParamVal")
                    ))
                    ->assignParamValue(new AssignedParamValue(
                        new ParamByExactName("newA"),
                        new ScalarValue("firstParamVal")
                    ))
            )
            ->addMethodCall(
                (new MethodCall(new MethodByExactName("__construct")))
                    ->assignParamValue(new AssignedParamValue(
                        new ParamByExactName("passedToConstructor"),
                        new ScalarValue("constructorParam")
                    ))
            );

        $rebuiltObject = $this->getRebuiltObjectBy($plan);
        /* @var $rebuiltObject TestClass */
        $this->assertInstanceOf(TestClass::class, $rebuiltObject);
        $this->assertEquals("constructorParam", $rebuiltObject->passedToConstructor);
        $this->assertEquals("firstParamVal", $rebuiltObject->memberA);
        $this->assertEquals("secondParamVal", $rebuiltObject->memberB);
    }
}