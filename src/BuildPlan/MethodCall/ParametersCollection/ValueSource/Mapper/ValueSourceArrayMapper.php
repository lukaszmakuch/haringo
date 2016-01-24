<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Mapper;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Mapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Mapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;

interface ValueSourceArrayMapper
{
    /**
     * @return array may contain: scalar values, NULL, arrays and also arrays of 
     * any of these.
     * @throws ImpossibleToMapObject
     */
    public function mapToArray(ValueSource $valueSource);
    
    /**
     * @param array $previouslyMappedArray array that was the result of calling
     * the mapToArray method of this class.
     * 
     * @return ValueSource
     * @throws ImpossibleToBuildFromArray
     */
    public function mapToObject(array $previouslyMappedArray);
}