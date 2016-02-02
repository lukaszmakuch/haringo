<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSourceResolver\Impl\BuildPlanValueSource;

use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\ObjectBuilder\ObjectBuilder;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanValueSource;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\Exception\ImpossibleToResolveValue;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\ValueResolver;

/**
 * Resolves values based on build plans.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuildPlanValueSourceResolver  implements ValueResolver
{
    private $objectBuilder;
    
    /**
     * Provides builder used to build objects based on given value sources.
     * 
     * @param ObjectBuilder $objectBuilder
     */
    public function __construct(ObjectBuilder $objectBuilder)
    {
        $this->objectBuilder = $objectBuilder;
    }

    public function resolveValueFrom(ValueSource $source)
    {
        try {
            /* @var $source BuildPlanValueSource */
            return $this->objectBuilder->buildObjectBasedOn($source->getBuildPlan());
        } catch (ImpossibleToFinishBuildPlan $e) {
            throw new ImpossibleToResolveValue();
        }
    }
}

