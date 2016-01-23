<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\Resolver\Impl;

use lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\BuildingProcess\FullClassPathSource\Resolver\FullClassPathResolver;

class ExactClassPathResolver implements FullClassPathResolver
{
    public function resolve(FullClassPathSource $source)
    {
        /* @var $source ExactClassPath */
        return $source->getHeldFullClassPath();
    }
}
