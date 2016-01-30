<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl;

use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\ClassSourceResolver\FullClassPathResolver;

class ExactClassPathResolver implements FullClassPathResolver
{
    public function resolve(FullClassPathSource $source)
    {
        /* @var $source ExactClassPath */
        return $source->getHeldFullClassPath();
    }
}
