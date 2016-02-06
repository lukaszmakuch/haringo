<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo;

use lukaszmakuch\Haringo\Builder\Impl\HaringoBuilderImpl;
use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\MethodSelectorMap;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;
use PHPUnit_Framework_TestCase;

abstract class HaringoTestTpl extends PHPUnit_Framework_TestCase
{
    /**
     * @var Haringo
     */
    protected $builder;

    protected function setUp()
    {
        $HaringoBuilder = new HaringoBuilderImpl();
        $HaringoBuilder->setParamSelectorMap($this->getParamSelectorMap());
        $HaringoBuilder->setClassSourceMap($this->getClassSourceMap());
        $HaringoBuilder->setMethodSelectorMap($this->getMethodSelectorMap());
        $this->builder = $HaringoBuilder->build();
    }
    
    protected function getRebuiltObjectBy(BuildPlan $plan)
    {
        $serializedPlan = $this->builder->serializeBuildPlan($plan);
        $this->assertTrue(is_string($serializedPlan));
        $deserializedPlan = $this->builder->deserializeBuildPlan($serializedPlan);
        $builtObject = $this->builder->buildObjectBasedOn($deserializedPlan);
        return $builtObject;
    }
    
    /**
     * @return ParamSelectorMap
     */
    protected function getParamSelectorMap()
    {
        return new ParamSelectorMap();
    }
    
    /**
     * @return ClassPathSourceMap
     */
    protected function getClassSourceMap()
    {
        return new ClassPathSourceMap();
    }
    
    /**
     * @return MethodSelectorMap
     */
    protected function getMethodSelectorMap()
    {
        return new MethodSelectorMap();
    }
}
