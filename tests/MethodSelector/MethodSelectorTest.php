<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelector;

use lukaszmakuch\ObjectBuilder\BuilderTestTpl;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\MethodSelectorFromMap;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodSelectorFromMap\FullMethodIdentifier;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodSelectorFromMap\MethodSelectorMap;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\TestClass;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;

class MethodSelectorTest extends BuilderTestTpl
{
    protected function getMethodSelectorMap()
    {
        $map = new MethodSelectorMap();
        $map->addSelector(
            "mapped_method_constructor",
            new FullMethodIdentifier(
                new ExactClassPath(TestClass::class),
                ConstructorSelector::getInstance()
            )
        );
        return $map;
    }
    
    public function testConstructorSelector()
    {
        $this->checkMethodSelector(ConstructorSelector::getInstance());
    }
    
    public function testSelectorByName()
    {
        $this->checkMethodSelector(new ExactMethodName("__construct"));
    }
    
    public function testExceptionWhenNoMethodWithGivenName()
    {
        $this->assertExceptionFor(new ExactMethodName("methodThatDoesNotExist"));
    }
    
    public function testSelectorFromMap()
    {
        $this->checkMethodSelector(new MethodSelectorFromMap("mapped_method_constructor"));
    }
    
    public function testExceptionWhenNoKeyInMap()
    {
        $this->assertExceptionFor(new MethodSelectorFromMap("key_which_does_not_exist"));
    }
    
    /**
     * Checks whether this selector grabs
     *  the constructor method of the TestClass class.
     */
    private function checkMethodSelector(MethodSelector $selector)
    {
        $plan = (new NewInstanceBuildPlan())
            ->setClassSource(new ExactClassPath(TestClass::class));
        
        $plan->addMethodCall(
            (new MethodCall($selector))
                ->assignParamValue(new AssignedParamValue(
                    new ParamByPosition(0),
                    new ScalarValue("newValue")
                ))
        );
        
        /* @var $object TestClass */
        $object = $this->getRebuiltObjectBy($plan);
        $this->assertEquals("newValue", $object->passedToConstructor);
    }

    /**
     * Assumes that trying to use this selector with cause
     * throwing an exception.
     * 
     * @param MethodSelector $selector
     */
    protected function assertExceptionFor(MethodSelector $selector)
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = (new NewInstanceBuildPlan())
            ->setClassSource(new ExactClassPath(TestClass::class))
            ->addMethodCall(new MethodCall($selector));
        
        $this->getRebuiltObjectBy($plan);
    }
}
