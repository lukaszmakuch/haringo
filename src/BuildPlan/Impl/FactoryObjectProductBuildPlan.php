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
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

/**
 * Describes how a factory product should be build.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FactoryObjectProductBuildPlan implements BuildPlan
{
    private $factoryObjectSource;
    private $buildMethodCall;
    
    /**
     * Sets source of the object used as a factory. 
     * 
     * Equivalent to:
     * $factory = new Factory();
     * 
     * @param ValueSource $objectSource
     * @return FactoryObjectProductBuildPlan self
     */
    public function setFactoryObject(ValueSource $objectSource)
    {
        $this->factoryObjectSource = $objectSource;
        return $this;
    }
    
    /**
     * Sets the method call which builds the product.
     * 
     * Equivalent to:
     * $product = $factory->get();
     * 
     * @param MethodCall $call
     * @return FactoryObjectProductBuildPlan self
     */
    public function setBuildMethodCall(MethodCall $call)
    {
        $this->buildMethodCall = $call;
        return $this;
    }
    
    /**
     * @return ValueSource
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
