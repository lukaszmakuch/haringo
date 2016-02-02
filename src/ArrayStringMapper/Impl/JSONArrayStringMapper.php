<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ArrayStringMapper\Impl;

use lukaszmakuch\ObjectBuilder\ArrayStringMapper\ArrayStringMapper;
use lukaszmakuch\ObjectBuilder\ArrayStringMapper\Exception\UnableToMapToArray;

/**
 * Maps arrays to and from JSON.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class JSONArrayStringMapper implements ArrayStringMapper
{
    public function arrayToString(array $inputArray)
    {
        return json_encode($inputArray);
    }

    public function stringToArray($inputString)
    {
        $decoded = json_decode($inputString, true);
        if (null === $decoded) {
            throw new UnableToMapToArray();
        }
        
        return $decoded;
    }
}
