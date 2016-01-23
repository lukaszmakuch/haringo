<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildingProcess\Factory\Impl\BuildingProcessFactoryImpl;
use lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\Resolver\Impl\ExactClassPathResolver;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\Impl\MethodCallImpl;
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
            new BuildingProcessFactoryImpl(),
            new ExactClassPathResolver(),
            new ExactMethodNameMatcher(),
            new ParamByExactNameMatcher(),
            new ScalarValueResolver()
        );
        
        $buildingProcess = $builder->startBuildingProcess()
            ->setClassSource(new ExactClassPath(TestClass::class))
            ->addMethodCall(
                (new MethodCallImpl(new ExactMethodName("setMembers")))
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
                (new MethodCallImpl(new ExactMethodName("__construct")))
                    ->assignParamValue(
                        new ParamByExactName("passedToConstructor"),
                        new ScalarValue("constructorParam")
                    )
            );

        $builtObject = $builder->finishBuildingProcess($buildingProcess);
        
        /* @var $builtObject TestClass */
        $this->assertInstanceOf(TestClass::class, $builtObject);
        $this->assertEquals("constructorParam", $builtObject->passedToConstructor);
        $this->assertEquals("firstParamVal", $builtObject->memberA);
        $this->assertEquals("secondParamVal", $builtObject->memberB);
    }
}