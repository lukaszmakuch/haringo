<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;

class StaticFactoryProductBuildPlan implements BuildPlan
{
    private $factoryClass;
    
    private $factoryMethod;
    
    /**
     * @param FullClassPathSource $classSource
     * @return StaticFactoryProductBuildPlan self
     */
    public function setFactoryClass(FullClassPathSource $classSource)
    {
        $this->factoryClass = $classSource;
        return $this;
    }
    
    /**
     * @param MethodCall $call
     * @return StaticFactoryProductBuildPlan self
     */
    public function setFactoryMethodCall(MethodCall $call)
    {
        $this->factoryMethod = $call;
        return $this;
    }
    
    /**
     * @return FullClassPathSource
     */
    public function getFactoryClass()
    {
        return $this->factoryClass;
    }
    
    /**
     * @return MethodCall
     */
    public function getFactoryMethodCall()
    {
        return $this->factoryMethod;
    }
}