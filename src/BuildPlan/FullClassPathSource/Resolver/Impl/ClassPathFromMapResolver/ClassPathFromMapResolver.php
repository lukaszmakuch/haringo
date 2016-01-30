<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Resolver\Impl\ClassPathFromMapResolver;

use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Impl\ClassPathFromMap;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Resolver\FullClassPathResolver;

class ClassPathFromMapResolver implements FullClassPathResolver
{
    private $sources;
    private $actualResolver;
    
    public function __construct(
        ClassPathSourceMap $sources,
        FullClassPathResolver $actualResolver
    ) {
        $this->sources = $sources;
        $this->actualResolver = $actualResolver;
    }
    
    public function resolve(FullClassPathSource $source)
    {
        /* @var $source ClassPathFromMap */
        $actualSource = $this->sources->getSourceBy($source->getKeyFromMap());
        return $this->actualResolver->resolve($actualSource);
    }
}
