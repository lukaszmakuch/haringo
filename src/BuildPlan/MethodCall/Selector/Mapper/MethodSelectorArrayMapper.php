<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Mapper;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Mapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Mapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\MethodSelector;

interface MethodSelectorArrayMapper
{
    /**
     * @param MethodSelector $selector
     * 
     * @return array
     * @throws ImpossibleToMapObject
     */
    public function mapToArray(MethodSelector $selector);
    
    /**
     * @param array $previouslyMappedArray result of calling the mapToArray method.
     * 
     * @return MethodSelector
     * @throws ImpossibleToBuildFromArray
     */
    public function mapToObject(array $previouslyMappedArray);
}