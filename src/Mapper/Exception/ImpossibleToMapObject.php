<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Mapper\Exception;

use RuntimeException;

/**
 * Thrown when it's impossible to map some object to an array.
 */
class ImpossibleToMapObject extends RuntimeException
{
}