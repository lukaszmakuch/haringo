<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\MethodSelectorMapper\Impl;

use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\MethodSelectorMapper\MethodSelectorArrayMapper;

/**
 * Maps exact method name selectors to arrays.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class MethodByExactNameArrayMapperImpl implements MethodSelectorArrayMapper
{
    public function mapToArray($objectToMap)
    {
        /* @var $selector MethodByExactName */
        $selector = $objectToMap;
        return [$selector->getMethodByExactName()];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        return new MethodByExactName($previouslyMappedObject[0]);
    }
}
