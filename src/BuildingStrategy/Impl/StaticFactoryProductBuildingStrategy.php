<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildingStrategy\Impl;

use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\StaticFactoryProductBuildPlan;

/**
 * Builds an object based on a description how should they be build
 * using a static factory method.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class StaticFactoryProductBuildingStrategy extends FactoryProductBuildingStrategyTpl
{
    public function buildObjectBasedOn(BuildPlan $p)
    {
        /* @var $p StaticFactoryProductBuildPlan */
        $factoryMethod = $this->findFactoryMethod(
            $this->getReflectedClassBasedOn($p->getFactoryClass()),
            $p->getFactoryMethodCall()->getSelector()
        );

        $builtObject = $this->callSpecifiedMethod(
            $factoryMethod,
            null, //static
            $p->getFactoryMethodCall()->getAssignedParamValues()
        );

        $this->buildPlanByBuildObject->attach($builtObject, $p);
        return $builtObject;
    }
}
