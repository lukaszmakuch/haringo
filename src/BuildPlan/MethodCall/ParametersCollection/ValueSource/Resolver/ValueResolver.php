<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Resolver;

use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Resolver\Exception\ImpossibleToResolveValue;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;

/**
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ValueResolver
{
    /**
     * @return mixed resolved value
     * @throws ImpossibleToResolveValue
     */
    public function resolveValueFrom(ValueSource $source);
}
