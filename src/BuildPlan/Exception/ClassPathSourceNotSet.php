<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Exception;

/**
 * Thown when trying to read a class path source that hasn't been set yet.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ClassPathSourceNotSet extends \RuntimeException
{
}
