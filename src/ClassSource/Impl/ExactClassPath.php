<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ClassSource\Impl;

use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;

/**
 * Represents the exact full class path of an object that should be created.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ExactClassPath implements FullClassPathSource
{
    private $heldFullClassPath;
    
    /**
     * @param String $heldFullClassPath full class path
     */
    public function __construct($heldFullClassPath)
    {
        $this->heldFullClassPath = $heldFullClassPath;
    }
    
    /**
     * @return String full class path
     */
    public function getHeldFullClassPath()
    {
        return $this->heldFullClassPath;
    }
}