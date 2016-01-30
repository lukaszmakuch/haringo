<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\ClassSource\Resolver\Impl\ExactClassPathResolver;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Matcher\Impl\ParamByExactNameMatcher;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\ValueSource\Resolver\Impl\ScalarValueResolver;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\MethodSelector\Matcher\Impl\ExactMethodNameMatcher;
use lukaszmakuch\ObjectBuilder\Exception\BuildPlanNotFound;
use lukaszmakuch\ObjectBuilder\TestClass;
use PHPUnit_Framework_TestCase;
use stdClass;

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
        
        $buildPlan = (new BuildPlan(new ExactClassPath(TestClass::class)))
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
    
    public function testFetchingBuildPlanByBuiltObject()
    {
        $builder = new ObjectBuilderImpl(
            new ExactClassPathResolver(),
            new ExactMethodNameMatcher(),
            new ParameterListGenerator(
                new ParamByExactNameMatcher(),
                new ScalarValueResolver()
            )
        );
        
        $buildPlan = (new BuildPlan(new ExactClassPath(stdClass::class)));
        
        $builtObject = $builder->buildObjectBasedOn($buildPlan);
        
        $fetchedBuildPlan = $builder->getBuildPlanUsedToBuild($builtObject);
        
        $this->assertTrue($fetchedBuildPlan === $buildPlan);
    }
    
    public function testExceptionWhenImpossibleToFetchBuildPlan()
    {
        $builder = new ObjectBuilderImpl(
            new ExactClassPathResolver(),
            new ExactMethodNameMatcher(),
            new ParameterListGenerator(
                new ParamByExactNameMatcher(),
                new ScalarValueResolver()
            )
        );
        
        $this->setExpectedException(BuildPlanNotFound::class);
        $builder->getBuildPlanUsedToBuild(new stdClass());
    }
}