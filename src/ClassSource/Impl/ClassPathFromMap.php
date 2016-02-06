<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ClassSource\Impl;

use lukaszmakuch\Haringo\ClassSource\FullClassPathSource;

/**
 * Holds key from the map of class path sources.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ClassPathFromMap implements FullClassPathSource
{
    private $keyFromMap;
    
    /**
     * @param String $keyFromMap key from the map
     */
    public function __construct($keyFromMap)
    {
        $this->keyFromMap = $keyFromMap;
    }

    /**
     * @return string key from the map
     */
    public function getKeyFromMap()
    {
        return $this->keyFromMap;
    }
}
