<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\MethodSelector;

use lukaszmakuch\Haringo\HaringoTestTpl;
use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodFromMap;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\FullMethodIdentifier;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\MethodSelectorMap;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;
use lukaszmakuch\Haringo\TestClass;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;

class MethodSelectorTest extends HaringoTestTpl
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
        $this->checkMethodSelector(new MethodByExactName("__construct"));
    }
    
    public function testExceptionWhenNoMethodWithGivenName()
    {
        $this->assertExceptionFor(new MethodByExactName("methodThatDoesNotExist"));
    }
    
    public function testSelectorFromMap()
    {
        $this->checkMethodSelector(new MethodFromMap("mapped_method_constructor"));
    }
    
    public function testExceptionWhenNoKeyInMap()
    {
        $this->assertExceptionFor(new MethodFromMap("key_which_does_not_exist"));
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
