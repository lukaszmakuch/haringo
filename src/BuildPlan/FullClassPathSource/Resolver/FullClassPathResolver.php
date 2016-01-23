<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Resolver;

use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Resolver\Exception\UnsupportedSource;

/**
 * Resolves full class path.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface FullClassPathResolver
{
    /**
     * @return String full class path
     * @throws UnsupportedSource
     */
    public function resolve(FullClassPathSource $source);
}
