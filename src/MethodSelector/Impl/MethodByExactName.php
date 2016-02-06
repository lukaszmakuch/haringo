<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelector\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelector\MethodSelector;

/**
 * Allows to select a method by its exact name.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MethodByExactName implements MethodSelector
{
    private $methodName;
    
    /**
     * Sets desired method exact name.
     * 
     * @param String $methodName
     */
    public function __construct($methodName)
    {
        $this->methodName = $methodName;
    }
    
    /**
     * @return String method name 
     */
    public function getMethodByExactName()
    {
        return $this->methodName;
    }
}
