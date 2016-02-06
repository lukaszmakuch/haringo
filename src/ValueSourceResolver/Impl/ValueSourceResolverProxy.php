<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ValueSourceResolver\Impl;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;
use lukaszmakuch\Haringo\ValueSourceResolver\Exception\ImpossibleToResolveValue;
use lukaszmakuch\Haringo\ValueSourceResolver\ValueResolver;
use lukaszmakuch\Haringo\ValueSource\ValueSource;

/**
 * Allows to assign different value resolvers to different value source classes.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ValueSourceResolverProxy implements ValueResolver
{
    private $actualResolvers;
    
    public function __construct()
    {
        $this->actualResolvers = new ClassBasedRegistry();
    }
    
    /**
     * Adds a resolver to support some ValueSource class.
     * 
     * @param ValueResolver $r resolver to use
     * @param String $targetValueSourceClass ValueSource class to support
     */
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
