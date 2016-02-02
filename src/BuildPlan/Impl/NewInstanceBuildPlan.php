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

/**
 * Describes how a new class instance should be build.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class NewInstanceBuildPlan implements BuildPlan
{
    private $classSource;
    private $methodCalls = [];
    
    /**
     * Sets class source.
     * 
     * Equivalent to:
     * $product = new Product();
     * 
     * @param FullClassPathSource $classSource
     * @return NewInstanceBuildPlan
     */
    public function setClassSource(FullClassPathSource $classSource)
    {
        $this->classSource = $classSource;
        return $this;
    }
    
    /**
     * Sets a method called on the previously build object.
     * 
     * Equivalent to:
     * $product->method($param);
     * 
     * @param MethodCall $call
     * @return \lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan
     */
    public function addMethodCall(MethodCall $call)
    {
        $this->methodCalls[] = $call;
        return $this;
    }
    
    /**
     * @return FullClassPathSource
     */
    public function getClassSource()
    {
        return $this->classSource;
    }
    
    /**
     * @return MethodCall[]
     */
    public function getAllMethodCalls()
    {
        return $this->methodCalls;
    }
}
