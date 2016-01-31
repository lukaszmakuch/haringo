<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ArrayStringMapper;

use lukaszmakuch\ObjectBuilder\ArrayStringMapper\Exception\UnableToMapToArray;
use lukaszmakuch\ObjectBuilder\ArrayStringMapper\Exception\UnableToMapToString;

/**
 * Maps an array to a string that may be later mapped back into an array.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ArrayStringMapper
{
    /**
     * @return String the given array mapped to a string
     * @throws UnableToMapToString
     */
    public function arrayToString(array $inputArray);
    
    /**
     * @return array the given string mapped to an array
     * @throws UnableToMapToArray
     */
    public function stringToArray($inputString);
}