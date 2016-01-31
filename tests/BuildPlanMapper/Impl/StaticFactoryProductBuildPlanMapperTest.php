<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlanMapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayMapperTest;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\StaticFactoryBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\StaticFactoryProductBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlanMapper\Impl\StaticFactoryProductBuildPlanMapper;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\ClassSourceMapper\Impl\ExactClassPathArrayMapper;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodCallMapper\MethodCallArrayMapper;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\MethodSelectorMapper\Impl\ExactMethodNameArrayMapperImpl;
use lukaszmakuch\ObjectBuilder\ParamSelectorMapper\Impl\ParamByExactNameMapperImpl;
use lukaszmakuch\ObjectBuilder\ParamValueMapper\AssignedParamValueArrayMapper;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\Impl\ScalarValueMapper;

class StaticFactoryProductBuildPlanMapperTest extends ArrayMapperTest
{
    public function testCorrectMapping()
    {
        $mapper = new StaticFactoryProductBuildPlanMapper(
            new ExactClassPathArrayMapper(),
            new MethodCallArrayMapper(
                new ExactMethodNameArrayMapperImpl(),
                new AssignedParamValueArrayMapper(
                    new ParamByExactNameMapperImpl(),
                    new ScalarValueMapper()
                )
            )
        );
        
        $buildPlan = (new StaticFactoryProductBuildPlan())
            ->setFactoryClass(new ExactClassPath("FactoryClass"))
            ->setFactoryMethodCall(new MethodCall(new ExactMethodName("FactoryMethod")));
        
        $mapped = $mapper->mapToArray($buildPlan);
        $this->assertAllowedDataTypesWithin($mapped);
        /* @var $rebuilt StaticFactoryBuildPlan */ 
        $rebuilt = $mapper->mapToObject($mapped);
        
        $this->assertTrue(
            $rebuilt->getFactoryClass()->getHeldFullClassPath() == "FactoryClass"
        );
        
        $this->assertTrue(
            $rebuilt->getFactoryMethodCall()->getSelector()->getExactMethodName() == "FactoryMethod"
        );
    }
}