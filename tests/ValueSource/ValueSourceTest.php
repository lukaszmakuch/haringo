<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSource;

use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\BuilderTestTpl;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\TestClass;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ArrayValueSource;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanValueSource;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;

class ValueSourceTest extends BuilderTestTpl
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
        $arraySource = new ArrayValueSource();
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
        
        $builPlanSource = new BuildPlanValueSource($otherPlan);
        
        $this->assertInstanceOf(
            TestClass::class,
            $this->getRebuiltResultOf($builPlanSource)
        );
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
