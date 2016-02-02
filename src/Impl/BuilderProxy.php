<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;
use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\Exception\BuildPlanNotFound;
use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\ObjectBuilder\ObjectBuilder;

/**
 * Proxy that allows to assign some builder to target build plan class.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuilderProxy implements ObjectBuilder
{
    private $builderByBuildPlan;
    
    /**
     * @var ObjectBuilder[] 
     */
    private $allBuilders;
    
    public function __construct()
    {
        $this->builderByBuildPlan = new ClassBasedRegistry();
        $this->allBuilders = [];
    }
   
    /**
     * Adds a builder to the proxy.
     * 
     * @param ObjectBuilder $actualBuilder builder to use
     * @param String $targetBuildPlanClass BuildPlan class that should be 
     * supproted by this builder
     */
    public function registerBuilder(
        ObjectBuilder $actualBuilder,
        $targetBuildPlanClass
    ) {
        $this->builderByBuildPlan->associateValueWithClasses(
            $actualBuilder,
            [$targetBuildPlanClass]
        );
        $this->allBuilders[] = $actualBuilder;
    }
    
    public function buildObjectBasedOn(BuildPlan $p)
    {
        $actualBuilder = $this->getActualBuilderBy($p);
        return $actualBuilder->buildObjectBasedOn($p);
    }

    public function getBuildPlanUsedToBuild($previouslyBuiltObject)
    {
        foreach ($this->allBuilders as $builder) {
            try {
                $builder->getBuildPlanUsedToBuild($previouslyBuiltObject);
            } catch (BuildPlanNotFound $e) {
                //it's not the builder we are looking for, keep looking
            }
        }
        
        //builder of this object has not been found
        throw new BuildPlanNotFound();
    }
    
    /**
     * @param BuildPlan $p
     * @return ObjectBuilder
     * 
     * @throw ImpossibleToFinishBuildPlan
     */
    private function getActualBuilderBy(BuildPlan $p)
    {
        try {
            return $this->builderByBuildPlan->fetchValueByObjects([$p]);
        } catch (ValueNotFound $e) {
            throw new ImpossibleToFinishBuildPlan();
        }
    }
}
