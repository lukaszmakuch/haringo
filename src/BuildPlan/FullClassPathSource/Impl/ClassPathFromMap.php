<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\FullClassPathSource;

class ClassPathFromMap implements FullClassPathSource
{
    private $keyFromMap;
    
    /**
     * @param String $keyFromMap
     */
    public function __construct($keyFromMap)
    {
        $this->keyFromMap = $keyFromMap;
    }

    /**
     * @return string
     */
    public function getKeyFromMap()
    {
        return $this->keyFromMap;
    }
}
