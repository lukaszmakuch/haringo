<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodCallMapper\Selector\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\MethodCallMapper\Selector\MethodSelectorArrayMapper;

/**
 * Uses array formated like that:
 * [String]
 * where the only String value (with index 0) is the selector value.
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
