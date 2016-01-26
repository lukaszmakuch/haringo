<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper;

use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Exception\ImpossibleToBuildFromArray;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Exception\ImpossibleToMapObject;

/**
 * Allows to map an object to an array that may contain: 
 *     - scalar values
 *     - NULL
 *     - arrays (also arrays of any of these)
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface SerializableArrayMapper
{
    /**
     * @param mixed $objectToMap object that should be mapped to an array
     * 
     * @return array may contain: scalar values, NULL, arrays and also arrays of 
     * any of these.
     * @throws ImpossibleToMapObject
     */
    public function mapToArray($objectToMap);
    
    /**
     * @param array $previouslyMappedObject array that was the result of calling
     * the mapToArray method of this class.
     * 
     * @return mixed object identical to the one passed to the mapToArray
     * in order to get the array passed as a parameter to this method.
     * @throws ImpossibleToBuildFromArray
     */
    public function mapToObject(array $previouslyMappedObject);
}