<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ClassSourceResolver;

use lukaszmakuch\Haringo\ClassSource\FullClassPathSource;
use lukaszmakuch\Haringo\ClassSourceResolver\Exception\UnsupportedSource;

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
