<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\Resolver\Impl\ExactClassPathResolver;
use lukaszmakuch\ObjectBuilder\BuildingProcess\Impl\BuildingProcessImpl;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\Selector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\Selector\Matcher\Impl\ParamByExactNameMatcher;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\ValueSource\Impl\ScalarValue;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\ParametersCollection\ValueSource\Resolver\Impl\ScalarValueResolver;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\Selector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\Selector\Matcher\Impl\ExactMethodNameMatcher;
use lukaszmakuch\ObjectBuilder\TestClass;
use PHPUnit_Framework_TestCase;

class ObjectBuilderImplTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectBuild()
    {
        $builder = new ObjectBuilderImpl(
            new ExactClassPathResolver(),
            new ExactMethodNameMatcher(),
            new ParamByExactNameMatcher(),
            new ScalarValueResolver()
        );
        
        $buildingProcess = (new BuildingProcessImpl())
            ->setClassSource(new ExactClassPath(TestClass::class))
            ->addMethodCall(
                (new MethodCall(new ExactMethodName("setMembers")))
                    ->assignParamValue(
                        new ParamByExactName("newB"),
                        new ScalarValue("secondParamVal")
                    )
                    ->assignParamValue(
                        new ParamByExactName("newA"),
                        new ScalarValue("firstParamVal")
                    )
            )
            ->addMethodCall(
                (new MethodCall(new ExactMethodName("__construct")))
                    ->assignParamValue(
                        new ParamByExactName("passedToConstructor"),
                        new ScalarValue("constructorParam")
                    )
            );

        $builtObject = $builder->buildObjectBasedOn($buildingProcess);
        
        /* @var $builtObject TestClass */
        $this->assertInstanceOf(TestClass::class, $builtObject);
        $this->assertEquals("constructorParam", $builtObject->passedToConstructor);
        $this->assertEquals("firstParamVal", $builtObject->memberA);
        $this->assertEquals("secondParamVal", $builtObject->memberB);
    }
}