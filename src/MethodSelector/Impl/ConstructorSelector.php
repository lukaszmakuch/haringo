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
 * Singleton which allows to select a class constructor.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ConstructorSelector implements MethodSelector
{
    private static $instance = null;
    
    private function __construct()
    {
    }
    
    private function __clone()
    {
    }
    
    /**
     * @return MethodSelector self
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
}