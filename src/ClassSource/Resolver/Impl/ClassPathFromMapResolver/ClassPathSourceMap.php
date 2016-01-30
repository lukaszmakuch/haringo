<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ClassSource\Resolver\Impl\ClassPathFromMapResolver;

use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;

class ClassPathSourceMap
{
    private $actualSourceByKey = [];
    
    public function addSource($key, FullClassPathSource $actualSource)
    {
        $this->actualSourceByKey[$key] = $actualSource;
    }
    
    public function getSourceBy($key)
    {
        return $this->actualSourceByKey[$key];
    }
}