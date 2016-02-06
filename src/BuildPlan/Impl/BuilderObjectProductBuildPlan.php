<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildPlan\Impl;

use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\ValueSource\ValueSource;

/**
 * Describes how a product of a builder object should be build.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuilderObjectProductBuildPlan implements BuildPlan
{
    protected $builderSource;
    protected $settingCalls = [];
    protected $buildCall;
    
    /**
     * Sets source of the builder. It must be an object.
     * 
     * Equivalent to:
     * $builder = new Builder();
     * 
     * @param ValueSource $builderObjectSource
     * @return BuilderObjectProductBuildPlan self
     */
    public function setBuilderSource(ValueSource $builderObjectSource)
    {
        $this->builderSource = $builderObjectSource;
        return $this;
    }
    
    /**
     * Sets some settings. 
     * 
     * Equivalent to:
     * $builder->setProperty($value);
     * 
     * @param MethodCall $call
     */
    public function addSettingMethodCall(MethodCall $call)
    {
        $this->settingCalls[] = $call;
    }
    
    /**
     * Sets the main method used to actually build some product.
     * 
     * Equivalent to:
     * $product = $builder->build();
     * 
     * @param MethodCall $call
     */
    public function setBuildMethodCall(MethodCall $call)
    {
        $this->buildCall = $call;
    }
    
    /**
     * @return ValueSource previously set builder source
     */
    public function getBuilderSource()
    {
        return $this->builderSource;
    }
    
    /**
     * @return MethodCall call used to build the product
     */
    public function getBuildMethodCall()
    {
        return $this->buildCall;
    }
    
    /**
     * @return MethodCall[] calls used to set some properties
     */
    public function getAllSettingMethodCalls()
    {
        return $this->settingCalls;
    }
}
