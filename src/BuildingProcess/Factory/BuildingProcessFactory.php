<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\Factory;

interface BuildingProcessFactory
{
    /**
     * @return \lukaszmakuch\ObjectBuilder\BuildingProcess\BuildingProcess
     */
    public function getNewBuildingProcess();
}