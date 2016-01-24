<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Mapper\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Mapper\MethodSelectorArrayMapper;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\MethodSelector;

/**
 * Uses array formated like that:
 * [String]
 * where the only String value (with index 0) is the selector value.
 */
class ExactMethodNameArrayMapperImpl implements MethodSelectorArrayMapper
{
    public function mapToArray(MethodSelector $selector)
    {
        /* @var $selector ExactMethodName */
        return [$selector->getExactMethodName()];
    }

    public function mapToObject(array $previouslyMappedArray)
    {
        return new ExactMethodName($previouslyMappedArray[0]);
    }
}
