<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ClassSourceResolver\Impl\ClassPathFromMapResolver;

use lukaszmakuch\Haringo\ClassSource\FullClassPathSource;
use lukaszmakuch\Haringo\ClassSourceResolver\Exception\UnsupportedSource;

/**
 * Map of actual class path sources stored under some string keys.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ClassPathSourceMap
{
    private $actualSourceByKey = [];
    
    /**
     * Holds some class path source under some string key.
     * 
     * @param String $key
     * @param FullClassPathSource $actualSource
     */
    public function addSource($key, FullClassPathSource $actualSource)
    {
        $this->actualSourceByKey[$key] = $actualSource;
    }
    
    /**
     * @param String $key
     * @return FullClassPathSource
     * @throws UnsupportedSource
     */
    public function getSourceBy($key)
    {
        if (!isset($this->actualSourceByKey[$key])) {
            throw new UnsupportedSource();
        }

        return $this->actualSourceByKey[$key];
    }
}
