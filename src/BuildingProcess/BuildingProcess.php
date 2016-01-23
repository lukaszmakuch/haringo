<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess;

use lukaszmakuch\ObjectBuilder\BuildingProcess\Exception\ClassPathSourceNotSet;
use lukaszmakuch\ObjectBuilder\BuildingProcess\Exception\ImpossibleToCallMethod;
use lukaszmakuch\ObjectBuilder\BuildingProcess\Exception\ImpossibleToSetClassSource;
use lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildingProcess\MethodCall\MethodCall;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface BuildingProcess
{
    /**
     * @return BuildingProcess self
     * @throws ImpossibleToSetClassSource
     */
    public function setClassSource(FullClassPathSource $source);
    
    /**
     * @return BuildingProcess self
     * @throws ImpossibleToCallMethod
     */
    public function addMethodCall(MethodCall $call);
    
    /**
     * @return FullClassPathSource
     * @throws ClassPathSourceNotSet
     */
    public function getClassSource();
    
    /**
     * @return MethodCall[]
     */
    public function getAllMethodCalls();
}
