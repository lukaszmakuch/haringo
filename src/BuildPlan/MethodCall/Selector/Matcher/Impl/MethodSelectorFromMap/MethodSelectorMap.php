<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\Impl\MethodSelectorFromMap;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Impl\MethodSelectorFromMap;

class MethodSelectorMap
{
    private $fullMethodIdentifierByKey = [];
    
    public function addSelector(
        $key,
        FullMethodIdentifier $fullMethodId
    ) {
        if ($this->noIdentifierFor($key)) {
            $this->fullMethodIdentifierByKey[$key] = [];
        }
        
        $this->fullMethodIdentifierByKey[$key][] = $fullMethodId;
    }
    
    /**
     * @return FullMethodIdentifier[]
     */
    public function getMethodIdentifiersBy(MethodSelectorFromMap $selectorFromMap)
    {
        $key = $selectorFromMap->getKeyFromMap();
        if ($this->noIdentifierFor($key)) {
            return [];
        }
        
        return $this->fullMethodIdentifierByKey[$key];
    }
    
    /**
     * @param String $key
     * @return boolean
     */
    private function noIdentifierFor($key)
    {
        return !isset($this->fullMethodIdentifierByKey[$key]);
    }
}
