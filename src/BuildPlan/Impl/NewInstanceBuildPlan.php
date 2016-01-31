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

class NewInstanceBuildPlan implements BuildPlan
{
    private $classSource;
    private $methodCalls = [];
    
    public function setClassSource(FullClassPathSource $classSource)
    {
        $this->classSource = $classSource;
        return $this;
    }
    
    /**
     * @return FullClassPathSource
     */
    public function getClassSource()
    {
        return $this->classSource;
    }
    
    public function addMethodCall(MethodCall $call)
    {
        $this->methodCalls[] = $call;
        return $this;
    }
    
    /**
     * @return MethodCall[]
     */
    public function getAllMethodCalls()
    {
        return $this->methodCalls;
    }
}
