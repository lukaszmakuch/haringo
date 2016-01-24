<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Impl\ArrayStringMapper;

interface ArrayStringMapper
{
    public function arrayToString(array $inputArray);
    
    public function stringToArray($inputString);
}