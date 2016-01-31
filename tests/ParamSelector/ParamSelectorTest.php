<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelector;

use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\BuilderTestTpl;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodSelectorFromMap\FullMethodIdentifier;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamFromMap;
use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamFromMapMatcher\FullParamIdentifier;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\TestClass;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;

class ParamSelectorsTest extends BuilderTestTpl
{
    protected function getParamSelectorMap()
    {
        $map = new ParamSelectorMap();
        $map->addSelector(
            "second_param",
            new FullParamIdentifier(
                new FullMethodIdentifier(
                    new ExactClassPath(TestClass::class), 
                    new ExactMethodName("setMembers")
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
                (new MethodCall(new ExactMethodName("setMembers")))
                    ->assignParamValue(new AssignedParamValue(
                        $selector, 
                        new ScalarValue("secondParamVal")
                    ))
            );

        /* @var $rebuiltObject TestClass */
        $rebuiltObject = $this->getRebuiltObjectBy($plan);
        $this->assertEquals("secondParamVal", $rebuiltObject->memberB);
    }
}
