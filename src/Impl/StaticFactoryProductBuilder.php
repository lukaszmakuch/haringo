<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\StaticFactoryProductBuildPlan;

class StaticFactoryProductBuilder extends FactoryProductBuilderTpl
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
