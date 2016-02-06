<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\FetchingBuildPlan;

use DateTime;
use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\HaringoTestTpl;

class FetchingBuildPlanTest extends HaringoTestTpl
{
    public function testFetchingBuildPlan()
    {
        $plan = (new NewInstanceBuildPlan())
            ->setClassSource(new ExactClassPath(DateTime::class));
        
        $obj = $this->haringo->buildObjectBasedOn($plan);
        
        $this->assertTrue($plan === $this->haringo->getBuildPlanUsedToBuild($obj));
    }
}