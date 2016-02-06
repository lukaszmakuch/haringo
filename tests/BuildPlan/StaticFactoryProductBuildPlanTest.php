<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildPlan;

use lukaszmakuch\Haringo\HaringoTestTpl;
use lukaszmakuch\Haringo\BuildPlan\Impl\StaticFactoryProductBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;
use lukaszmakuch\Haringo\TestClass;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;

class TestStaticFactory
{
    public static function getProduct($configValue)
    {
        return new TestClass($configValue);
    }
}

class StaticFactoryProductBuildPlanTest extends HaringoTestTpl
{
    public function testCorrectBuild()
    {
        $plan = (new StaticFactoryProductBuildPlan())
            ->setFactoryClass(new ExactClassPath(TestStaticFactory::class))
            ->setFactoryMethodCall(
                (new MethodCall(new MethodByExactName("getProduct")))
                    ->assignParamValue(new AssignedParamValue(
                        new ParamByExactName("configValue"),
                        new ScalarValue("paramValue")
                    ))
            );

        /* @var $product TestClass */
        $product = $this->builder->buildObjectBasedOn($plan);
        
        $this->assertInstanceOf(TestClass::class, $product);
        $this->assertEquals("paramValue", $product->passedToConstructor);
    }
}