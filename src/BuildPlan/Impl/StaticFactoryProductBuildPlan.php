<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildPlan\Impl;

use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\ClassSource\FullClassPathSource;
use lukaszmakuch\Haringo\MethodCall\MethodCall;

/**
 * Describes how a product of a static factory method should be build.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class StaticFactoryProductBuildPlan implements BuildPlan
{
    private $factoryClass;
    
    private $factoryMethod;
    
    /**
     * Sets class with the static factory method.
     * 
     * Stands for that part:
     * Factory::
     * 
     * @param FullClassPathSource $classSource
     * @return StaticFactoryProductBuildPlan self
     */
    public function setFactoryClass(FullClassPathSource $classSource)
    {
        $this->factoryClass = $classSource;
        return $this;
    }
    
    /**
     * Sets the actual factory method.
     * 
     * Stands for that part:
     * ::build();
     * 
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
