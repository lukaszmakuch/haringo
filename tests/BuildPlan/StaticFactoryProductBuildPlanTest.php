<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan;

use lukaszmakuch\ObjectBuilder\BuilderTestTpl;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\StaticFactoryProductBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\TestClass;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;

class TestStaticFactory
{
    public static function getProduct($configValue)
    {
        return new TestClass($configValue);
    }
}

class StaticFactoryProductBuildPlanTest extends BuilderTestTpl
{
    public function testCorrectBuild()
    {
        $plan = (new StaticFactoryProductBuildPlan())
            ->setFactoryClass(new ExactClassPath(TestStaticFactory::class))
            ->setFactoryMethodCall(
                (new MethodCall(new ExactMethodName("getProduct")))
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