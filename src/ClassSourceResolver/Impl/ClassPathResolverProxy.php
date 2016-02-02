<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;
use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Exception\UnsupportedSource;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\FullClassPathResolver;

/**
 * Proxy of class path resolvers that 
 * allows to assign a resolver to some class source class.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ClassPathResolverProxy implements FullClassPathResolver
{
    private $resolversByClassSources;
    
    public function __construct()
    {
        $this->resolversByClassSources = new ClassBasedRegistry();
    }
    
    /**
     * Registers a new resolver.
     * 
     * @param FullClassPathResolver $r resolver to use
     * @param String $targetClassPathSourceClass full class path of value sources
     * that should be supported by this resolver
     */
    public function registerResolver(FullClassPathResolver $r, $targetClassPathSourceClass)
    {
        $this->resolversByClassSources->associateValueWithClasses(
            $r,
            [$targetClassPathSourceClass]
        );
    }
    
    public function resolve(FullClassPathSource $source)
    {
        try {
            /* @var $actualResolver FullClassPathResolver */
            $actualResolver = $this->resolversByClassSources->fetchValueByObjects([$source]);
            return $actualResolver->resolve($source);
        } catch (ValueNotFound $e) {
            throw new UnsupportedSource();
        }
    }
}
