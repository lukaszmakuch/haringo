<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Resolver\Impl;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Resolver\Exception\ImpossibleToResolveValue;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Resolver\ValueResolver;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;

class ValueSourceResolverProxy implements ValueResolver
{
    private $actualResolvers;
    
    public function __construct()
    {
        $this->actualResolvers = new ClassBasedRegistry();
    }
    
    public function registerResolver(ValueResolver $r, $targetValueSourceClass)
    {
        $this->actualResolvers->associateValueWithClasses($r, [$targetValueSourceClass]);
    }
    
    public function resolveValueFrom(ValueSource $source)
    {
        try {
            /* @var $suitableResolver ValueResolver */
            $suitableResolver = $this->actualResolvers->fetchValueByObjects([$source]);
            return $suitableResolver->resolveValueFrom($source);
        } catch (ValueNotFound $e) {
            throw new ImpossibleToResolveValue();
        }
    }
}
