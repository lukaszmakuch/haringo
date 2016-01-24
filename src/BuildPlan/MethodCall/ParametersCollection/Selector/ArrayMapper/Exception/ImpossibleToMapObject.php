<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ArrayMapper\Exception;

use RuntimeException;

/**
 * Thrown when it's impossible to map some 
 * \lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\Selector\ParameterSelector
 * to an array.
 */
class ImpossibleToMapObject extends RuntimeException
{
}