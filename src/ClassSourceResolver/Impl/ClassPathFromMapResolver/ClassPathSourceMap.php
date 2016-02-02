<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathFromMapResolver;

use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Exception\UnsupportedSource;

class ClassPathSourceMap
{
    private $actualSourceByKey = [];
    
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
