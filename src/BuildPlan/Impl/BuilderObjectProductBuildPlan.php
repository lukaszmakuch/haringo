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

class BuilderObjectProductBuildPlan implements BuildPlan
{
    protected $builderSource;
    protected $settingCalls = [];
    protected $buildCall;
    
    public function setBuilderSource(ValueSource $builderObjectSource)
    {
        $this->builderSource = $builderObjectSource;
        return $this;
    }
    
    public function addSettingMethodCall(MethodCall $call)
    {
        $this->settingCalls[] = $call;
    }
    
    public function setBuildMethodCall(MethodCall $call)
    {
        $this->buildCall = $call;
    }
    
    
    /**
     * @return ValueSource
     */
    public function getBuilderSource()
    {
        return $this->builderSource;
    }
    
    /**
     * @return MethodCall
     */
    public function getBuildMethodCall()
    {
        return $this->buildCall;
    }
    
    /**
     * @return MethodCall[]
     */
    public function getAllSettingMethodCalls()
    {
        return $this->settingCalls;
    }
}
