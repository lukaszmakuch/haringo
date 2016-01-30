<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Matcher\Impl\ParamFromMapMatcher;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\Impl\ParamFromMap;

class ParamSelectorMap
{
    private $fullParamIdentifierByKey = [];
    
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
