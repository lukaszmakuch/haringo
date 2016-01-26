<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl\ArrayStringMapper\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl\ArrayStringMapper\ArrayStringMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl\ArrayStringMapper\Exception\UnableToMapToArray;

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
