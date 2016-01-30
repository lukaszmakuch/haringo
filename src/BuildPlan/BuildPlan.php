<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan;

use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\MethodCall;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuildPlan
{
    private $methodCalls = [];
    private $classSource;
    
    public function __construct(FullClassPathSource $source)
    {
        $this->classSource = $source;
    }
    
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
}
