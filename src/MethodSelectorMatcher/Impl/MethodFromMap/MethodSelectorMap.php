<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap;

use lukaszmakuch\Haringo\MethodSelector\Impl\MethodFromMap;

/**
 * Maps of full method identifiers (class + method) under some string keys.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MethodSelectorMap
{
    private $fullMethodIdentifierByKey = [];
   
    /**
     * Stores some full method identifier under some given string key.
     * 
     * @param String $key
     * @param FullMethodIdentifier $fullMethodId
     */
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
    public function getMethodIdentifiersBy(MethodFromMap $selectorFromMap)
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
