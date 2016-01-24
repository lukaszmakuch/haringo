<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Resolver\Impl\ExactClassPathResolver;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Matcher\Impl\ParamByExactNameMatcher;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Resolver\Impl\ScalarValueResolver;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\Impl\ExactMethodNameMatcher;
use lukaszmakuch\ObjectBuilder\TestClass;
use PHPUnit_Framework_TestCase;

class ObjectBuilderImplTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectBuild()
    {
        $builder = new ObjectBuilderImpl(
            new ExactClassPathResolver(),
            new ExactMethodNameMatcher(),
            new ParameterListGenerator(
                new ParamByExactNameMatcher(),
                new ScalarValueResolver()
            )
        );
        
        $buildPlan = (new BuildPlan())
            ->setClassSource(new ExactClassPath(TestClass::class))
            ->addMethodCall(
                (new MethodCall(new ExactMethodName("setMembers")))
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
                (new MethodCall(new ExactMethodName("__construct")))
                    ->assignParamValue(new AssignedParamValue(
                        new ParamByExactName("passedToConstructor"),
                        new ScalarValue("constructorParam")
                    ))
            );

        $builtObject = $builder->buildObjectBasedOn($buildPlan);
        
        /* @var $builtObject TestClass */
        $this->assertInstanceOf(TestClass::class, $builtObject);
        $this->assertEquals("constructorParam", $builtObject->passedToConstructor);
        $this->assertEquals("firstParamVal", $builtObject->memberA);
        $this->assertEquals("secondParamVal", $builtObject->memberB);
    }
}