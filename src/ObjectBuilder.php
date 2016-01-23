<?php
/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ObjectBuilder
{
    /**
     * @return mixed object built based on the given BuildPlan
     * @throws ImpossibleToFinishBuildPlan
     */
    public function buildObjectBasedOn(BuildPlan $p);
}
