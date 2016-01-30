<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ClassSource\Resolver\Impl;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;
use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\ClassSource\Resolver\Exception\UnsupportedSource;
use lukaszmakuch\ObjectBuilder\ClassSource\Resolver\FullClassPathResolver;

class ClassPathResolverProxy implements FullClassPathResolver
{
    private $resolversByClassSources;
    
    public function __construct()
    {
        $this->resolversByClassSources = new ClassBasedRegistry();
    }
    
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
