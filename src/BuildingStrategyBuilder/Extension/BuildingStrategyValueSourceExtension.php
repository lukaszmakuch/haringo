<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildingStrategyBuilder\Extension;

use lukaszmakuch\Haringo\ValueSourceResolver\ValueResolver;

/**
 * Extension of a building strategy.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class BuildingStrategyValueSourceExtension
{
    private $resolver;
    private $supportedValueSourceClass;
    
    /**
     * Provides all what's needed to extend a building strategy in order
     * to support a new value source.
     * 
     * @param ValueResolver $resolver
     * @param type $supportedValueSourceClass
     */
    public function __construct(
        ValueResolver $resolver,
        $supportedValueSourceClass
    ) {
        $this->resolver = $resolver;
        $this->supportedValueSourceClass = $supportedValueSourceClass;
    }
    
    /**
     * Gets resolver able to resolve values of the ValueSource supported
     * by this extension.
     * 
     * @return ValueResolver
     */
    public function getResolver()
    {
        return $this->resolver;
    }
    
    /**
     * Gets a specified class of ValueSource objects supported by this extension.
     * 
     * @return String full class path
     */
    public function getSupportedValueSourceClass()
    {
        return $this->supportedValueSourceClass;
    }
}
