<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\Repository;

use lukaszmakuch\ObjectBuilder\BuildingProcess\BuildingProcess;
use lukaszmakuch\ObjectBuilder\BuildingProcess\Repository\Exception\BuildingProcessHasNotBeenStored;
use lukaszmakuch\ObjectBuilder\BuildingProcess\Repository\Exception\BuildingProcessNotFound;
use lukaszmakuch\ObjectBuilder\BuildingProcess\Repository\Exception\ImpossibleToAddBuildingProcess;
use lukaszmakuch\ObjectBuilder\BuildingProcess\Repository\Exception\ImpossibleToReadBuildingProcess;
use lukaszmakuch\ObjectBuilder\BuildingProcess\Repository\Exception\ImpossibleToRemoveBuildingProcess;
use lukaszmakuch\ObjectBuilder\BuildingProcess\Repository\StoredProcessId\StoredBuildingProcessId;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface BuildingProcessRepository
{
    /**
     * @return null
     * @throws ImpossibleToAddBuildingProcess
     */
    public function add(BuildingProcess $process);

    /**
     * @return null
     * @throws BuildingProcessHasNotBeenStored
     * @throws ImpossibleToRemoveBuildingProcess
     */
    public function remove(BuildingProcess $process);

    /**
     * @return StoredBuildingProcessId
     * @throws BuildingProcessHasNotBeenStored
     */
    public function getIdOfStored(BuildingProcess $process);

    /**
     * @return BuildingProcess
     * @throws BuildingProcessNotFound
     * @throws ImpossibleToReadBuildingProcess
     */
    public function fetchById(StoredBuildingProcessId $id);
}
