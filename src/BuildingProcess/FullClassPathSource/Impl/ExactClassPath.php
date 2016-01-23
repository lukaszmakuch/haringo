<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\Impl;

use lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\FullClassPathSource;

class ExactClassPath implements FullClassPathSource
{
    private $heldFullClassPath;
    
    public function __construct($heldFullClassPath)
    {
        $this->heldFullClassPath = $heldFullClassPath;
    }
    
    /**
     * @return String
     */
    public function getHeldFullClassPath()
    {
        return $this->heldFullClassPath;
    }
}