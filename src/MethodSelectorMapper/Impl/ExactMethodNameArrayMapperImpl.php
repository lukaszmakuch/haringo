<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelectorMapper\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\MethodSelectorMapper\MethodSelectorArrayMapper;

/**
 * Maps exact method name selectors to arrays.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ExactMethodNameArrayMapperImpl implements MethodSelectorArrayMapper
{
    public function mapToArray($objectToMap)
    {
        /* @var $selector ExactMethodName */
        $selector = $objectToMap;
        return [$selector->getExactMethodName()];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        return new ExactMethodName($previouslyMappedObject[0]);
    }
}
