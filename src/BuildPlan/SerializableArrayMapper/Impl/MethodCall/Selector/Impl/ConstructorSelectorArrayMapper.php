<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\Selector\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\Selector\MethodSelectorArrayMapper;

/**
 * Returns an empty array, because it doesn't need to store anything.
 */
class ConstructorSelectorArrayMapper implements MethodSelectorArrayMapper
{
    public function mapToArray($objectToMap)
    {
        return [];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        return ConstructorSelector::getInstance();
    }
}
