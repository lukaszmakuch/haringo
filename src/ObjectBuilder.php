<?php
/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder;

use lukaszmakuch\ObjectBuilder\BuildingProcess\BuildingProcess;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildingProcess;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ObjectBuilder
{
    /**
     * @return BuildingProcess
     */
    public function startBuildingProcess();
    
    /**
     * @return mixed object built based on the given BuildingProcess
     * @throws ImpossibleToFinishBuildingProcess
     */
    public function finishBuildingProcess(BuildingProcess $p);
}
