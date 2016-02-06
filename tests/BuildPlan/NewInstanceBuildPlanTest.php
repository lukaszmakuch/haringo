<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildPlan;

use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\HaringoTestTpl;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;
use lukaszmakuch\Haringo\TestClass;

class NewInstanceBuildPlanTest extends HaringoTestTpl
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