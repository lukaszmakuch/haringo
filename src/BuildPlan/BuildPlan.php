<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan;

use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\MethodCall;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuildPlan
{
    private $methodCalls = [];
    private $classSource;
    
    public function addMethodCall(MethodCall $call)
    {
        $this->methodCalls[] = $call;
        return $this;
    }

    public function getClassSource()
    {
        return $this->classSource;
    }

    public function getAllMethodCalls()
    {
        return $this->methodCalls;
    }

    public function setClassSource(FullClassPathSource $source)
    {
        $this->classSource = $source;
        return $this;
    }
}