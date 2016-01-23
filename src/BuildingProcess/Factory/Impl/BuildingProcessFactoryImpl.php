<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\Factory\Impl;

use lukaszmakuch\ObjectBuilder\BuildingProcess\Factory\BuildingProcessFactory;
use lukaszmakuch\ObjectBuilder\BuildingProcess\Impl\BuildingProcessImpl;

class BuildingProcessFactoryImpl implements BuildingProcessFactory
{
    public function getNewBuildingProcess()
    {
        return new BuildingProcessImpl();
    }

}