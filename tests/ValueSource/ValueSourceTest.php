<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ValueSource;

use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\Exception\UnableToBuild;
use lukaszmakuch\Haringo\HaringoTestTpl;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;
use lukaszmakuch\Haringo\TestClass;
use lukaszmakuch\Haringo\ValueSource\Impl\ArrayValue;
use lukaszmakuch\Haringo\ValueSource\Impl\BuildPlanResultValue;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;

class ValueSourceTest extends HaringoTestTpl
{
    public function testString()
    {
        $stringSource = new ScalarValue("abc");
        $this->assertEquals("abc", $this->getRebuiltResultOf($stringSource));
    }

    public function testInt()
    {
        $intSource = new ScalarValue(123);
        $this->assertTrue(123 === $this->getRebuiltResultOf($intSource));
    }

    public function testFloat()
    {
        $floatSource = new ScalarValue(2.50);
        $this->assertTrue(2.50 === $this->getRebuiltResultOf($floatSource));
    }

    public function testBoolean()
    {
        $booleanSource = new ScalarValue(true);
        $this->assertTrue(true === $this->getRebuiltResultOf($booleanSource));
    }
    
    public function testArray()
    {
        $arraySource = new ArrayValue();
        $arraySource->addValue("a", new ScalarValue(1));
        $arraySource->addValue(2, new ScalarValue("b"));
        
        $this->assertEquals(
            ["a" => 1, 2 => "b"],
            $this->getRebuiltResultOf($arraySource)
        );
    }
    
    public function testOtherBuildPlan()
    {
        $otherPlan = new NewInstanceBuildPlan();
        $otherPlan->setClassSource(new ExactClassPath(TestClass::class));
        
        $builPlanSource = new BuildPlanResultValue($otherPlan);
        
        $this->assertInstanceOf(
            TestClass::class,
            $this->getRebuiltResultOf($builPlanSource)
        );
    }
    
    public function testExceptionIfWrongValueSource()
    {
        $this->setExpectedException(UnableToBuild::class);
        $otherPlan = new NewInstanceBuildPlan();
        $otherPlan->setClassSource(new ExactClassPath("no_class_like_that"));
        $builPlanSource = new BuildPlanResultValue($otherPlan);
        $this->getRebuiltResultOf($builPlanSource);
    }
    
    /**
     * Puts it into a plan, then serializes the plan and deserializes it in order
     * to build a new object from which the value is read. 
     * 
     * @param ValueSource $source
     * @return mixed rebuilt value
     */
    protected function getRebuiltResultOf(ValueSource $source)
    {
        $plan = (new NewInstanceBuildPlan())
            ->setClassSource(new ExactClassPath(TestClass::class))
            ->addMethodCall(
                (new MethodCall(ConstructorSelector::getInstance()))
                    ->assignParamValue(new AssignedParamValue(
                        new ParamByExactName("passedToConstructor"),
                        $source
                    ))
            );

        /* @var $rebuiltObject TestClass */
        $rebuiltObject = $this->getRebuiltObjectBy($plan);
        return $rebuiltObject->passedToConstructor;
    }
}
