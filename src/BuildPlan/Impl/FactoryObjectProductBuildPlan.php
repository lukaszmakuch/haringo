<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\ValueSource\ObjectSource;


class FactoryObjectProductBuildPlan implements BuildPlan
{
    private $factoryObjectSource;
    private $buildMethodCall;
    
    public function setFactoryObject(ObjectSource $objectSource)
    {
        $this->factoryObjectSource = $objectSource;
    }
    
    public function setBuildMethodCall(MethodCall $call)
    {
        $this->buildMethodCall = $call;
    }
    
    /**
     * @return ObjectSource
     */
    public function getFactoryObjectSource()
    {
        return $this->factoryObjectSource;
    }
    
    /**
     * @return MethodCall
     */
    public function getBuildMethodCall()
    {
        return $this->buildMethodCall;
    }
}