<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodSelectorMapper\Impl;

use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\MethodSelectorMapper\MethodSelectorArrayMapper;

/**
 * Returns an empty array, because it doesn't need to store anything.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
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
