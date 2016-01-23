<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Repository\Exception;

/**
 * Thrown when a building process has been found but it's impossible to read it
 * for some reason.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ImpossibleToReadBuildPlan extends \RuntimeException
{
}
