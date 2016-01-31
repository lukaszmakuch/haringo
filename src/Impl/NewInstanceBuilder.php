<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;

class NewInstanceBuilder extends BuilderTpl
{
    public function buildObjectBasedOn(BuildPlan $p)
    {
        $reflectedClass = $this->getReflectedClassBasedOn($p->getClassSource());
        
        $builtObject = $reflectedClass->newInstanceWithoutConstructor();
        
        $this->callMethodsOf(
            $builtObject,
            $reflectedClass,
            $p->getAllMethodCalls()
        );
        
        $this->buildPlanByBuildObject->attach($builtObject, $p);
        return $builtObject;
    }
}
