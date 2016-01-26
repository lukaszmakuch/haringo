<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Exception;

use RuntimeException;

/**
 * Thrown when it's impossible to build an object from the given array.
 */
class ImpossibleToBuildFromArray extends RuntimeException
{
}