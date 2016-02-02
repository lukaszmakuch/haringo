<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamFromMapMatcher;

use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamFromMap;

/**
 * Holds definitions of some full parameter identifiers together with their
 * string keys.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ParamSelectorMap
{
    private $fullParamIdentifierByKey = [];
    
    /**
     * Adds a new full param identifier to the map.
     * 
     * @param String $key
     * @param FullParamIdentifier $paramId
     */
    public function addSelector($key, FullParamIdentifier $paramId)
    {
        if ($this->noIdentifierFor($key)) {
            $this->fullParamIdentifierByKey[$key] = [];
        }

        $this->fullParamIdentifierByKey[$key][] = $paramId;
    }
    
    /**
     * @return FullParamIdentifier[]
     */
    public function getParamSelectorBy(ParamFromMap $paramFromMap)
    {
        $key = $paramFromMap->getKeyFromMap();
        if ($this->noIdentifierFor($key)) {
            return [];
        }
        
        return $this->fullParamIdentifierByKey[$key];
    }
    
    /**
     * @param String $key
     * @return boolean
     */
    private function noIdentifierFor($key)
    {
        return !isset($this->fullParamIdentifierByKey[$key]);
    }
}
