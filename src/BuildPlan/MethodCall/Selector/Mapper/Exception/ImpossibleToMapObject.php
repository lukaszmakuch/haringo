<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Mapper\Exception;

use RuntimeException;

/**
 * Thrown when for some reason it's impossible 
 * to map a \lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\MethodSelector
 * to an array.
 */
class ImpossibleToMapObject extends RuntimeException
{
}