<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\Repository;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Repository\Exception\BuildPlanHasNotBeenStored;
use lukaszmakuch\ObjectBuilder\BuildPlan\Repository\Exception\BuildPlanNotFound;
use lukaszmakuch\ObjectBuilder\BuildPlan\Repository\Exception\ImpossibleToAddBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Repository\Exception\ImpossibleToReadBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Repository\Exception\ImpossibleToRemoveBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Repository\StoredProcessId\StoredBuildPlanId;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface BuildPlanRepository
{
    /**
     * @return null
     * @throws ImpossibleToAddBuildPlan
     */
    public function add(BuildPlan $process);

    /**
     * @return null
     * @throws BuildPlanHasNotBeenStored
     * @throws ImpossibleToRemoveBuildPlan
     */
    public function remove(BuildPlan $process);

    /**
     * @return StoredBuildPlanId
     * @throws BuildPlanHasNotBeenStored
     */
    public function getIdOfStored(BuildPlan $process);

    /**
     * @return BuildPlan
     * @throws BuildPlanNotFound
     * @throws ImpossibleToReadBuildPlan
     */
    public function fetchById(StoredBuildPlanId $id);
}
