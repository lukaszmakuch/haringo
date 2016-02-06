<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ParamSelector;

use lukaszmakuch\Haringo\HaringoTestTpl;
use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\FullMethodIdentifier;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamFromMap;
use lukaszmakuch\Haringo\ParamSelector\ParameterSelector;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher\FullParamIdentifier;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;
use lukaszmakuch\Haringo\TestClass;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;

class ParamSelectorsTest extends HaringoTestTpl
{
    protected function getParamSelectorMap()
    {
        $map = new ParamSelectorMap();
        $map->addSelector(
            "second_param",
            new FullParamIdentifier(
                new FullMethodIdentifier(
                    new ExactClassPath(TestClass::class), 
                    new MethodByExactName("setMembers")
                ),
                new ParamByExactName("newB")
            )
        );
        return $map;
    }
    
    public function testExactParamNameSelector()
    {
        $this->checkSelector(new ParamByExactName("newB"));
    }
    
    public function testWrongExactParamNameSelector()
    {
        $this->checkExceptionWhenWrongSelector(new ParamByExactName("does_not_exist"));
    }

    public function testParamByPositionSelector()
    {
        $this->checkSelector(new ParamByPosition(1));
    }
    
    public function testParamFromMap()
    {
        $this->checkSelector(new ParamFromMap("second_param"));
    }
    
    /**
     * checks whether the seletor matches newB parameter of TestClass method
     */
    protected function checkSelector(ParameterSelector $selector)
    {
        $plan = (new NewInstanceBuildPlan())
            ->setClassSource(new ExactClassPath(TestClass::class))
            ->addMethodCall(
                (new MethodCall(new MethodByExactName("setMembers")))
                    ->assignParamValue(new AssignedParamValue(
                        $selector, 
                        new ScalarValue("secondParamVal")
                    ))
            );

        /* @var $rebuiltObject TestClass */
        $rebuiltObject = $this->getRebuiltObjectBy($plan);
        $this->assertEquals("secondParamVal", $rebuiltObject->memberB);
    }
    
    protected function checkExceptionWhenWrongSelector(ParameterSelector $wrongSelector)
    {
        $this->setExpectedException(ImpossibleToFinishBuildPlan::class);
        $plan = (new NewInstanceBuildPlan())
            ->setClassSource(new ExactClassPath(TestClass::class))
            ->addMethodCall(
                (new MethodCall(new MethodByExactName("setMembers")))
                    ->assignParamValue(new AssignedParamValue(
                        $wrongSelector, 
                        new ScalarValue("secondParamVal")
                    ))
            );
        $this->haringo->buildObjectBasedOn($plan);
    }
}
