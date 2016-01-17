<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\Repository\Exception;

/**
 * Thrown when trying to use a building process that hasn't been stored yet 
 * like it were stored.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuildingProcessHasNotBeenStored extends \RuntimeException
{
}
