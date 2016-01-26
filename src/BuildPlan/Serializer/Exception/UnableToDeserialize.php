<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Serializer\Exception;

/**
 * Thrown when it's not possible to deserialize any BuildPlan from some string.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class UnableToDeserialize extends \RuntimeException
{
}