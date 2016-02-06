<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap;
use lukaszmakuch\Haringo\ClassSource\FullClassPathSource;
use lukaszmakuch\Haringo\MethodSelector\MethodSelector;

/**
 * Identifier exact method (together with the class it belongs to).
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FullMethodIdentifier
{
    private $classSource;
    private $methodSelector;
    
    /**
     * Provides dependencies.
     * 
     * @param FullClassPathSource $classSource determines within which class
     * the matcher should look for the desired method
     * @param MethodSelector $methodSelector selects methods
     */
    public function __construct(FullClassPathSource $classSource, MethodSelector $methodSelector)
    {
        $this->classSource = $classSource;
        $this->methodSelector = $methodSelector;
    }
    
    /**
     * @return FullClassPathSource
     */
    public function getClassSource()
    {
        return $this->classSource;
    }
    
    /**
     * @return MethodSelector
     */
    public function getMethodSelector()
    {
        return $this->methodSelector;
    }
}
