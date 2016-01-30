<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Resolver\Impl\BuildPlanValueSource;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Impl\BuildPlanValueSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Resolver\ValueResolver;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\ObjectBuilder;

class BuildPlanValueSourceResolver  implements ValueResolver
{
    private $objectBuilder;
    
    public function __construct(ObjectBuilder $objectBuilder)
    {
        $this->objectBuilder = $objectBuilder;
    }
    
    public function resolveValueFrom(ValueSource $source)
    {
        /* @var $source BuildPlanValueSource */
        return $this->objectBuilder->buildObjectBasedOn($source->getBuildPlan());
    }
}
