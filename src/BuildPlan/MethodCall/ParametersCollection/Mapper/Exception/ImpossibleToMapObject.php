<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Mapper\Exception;

use Zend\Stdlib\Exception\RuntimeException;

/**
 * Thrown when for some reason it's impossible 
 * to map an \lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\AssignedParamValue
 * to an array.
 */
class ImpossibleToMapObject extends RuntimeException
{
}