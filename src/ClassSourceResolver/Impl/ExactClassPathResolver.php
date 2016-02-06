<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ClassSourceResolver\Impl;

use lukaszmakuch\Haringo\ClassSource\FullClassPathSource;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\ClassSourceResolver\FullClassPathResolver;

/**
 * Resolves exact class paths.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ExactClassPathResolver implements FullClassPathResolver
{
    public function resolve(FullClassPathSource $source)
    {
        /* @var $source ExactClassPath */
        return $source->getHeldFullClassPath();
    }
}
