<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\Impl\MethodSelectorFromMap;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\MethodSelector;

/**
 * Identifier exact method (together with the class it belongs to).
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class FullMethodIdentifier
{
    private $classSource;
    private $methodSelector;
    
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
