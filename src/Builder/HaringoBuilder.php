<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\Builder;

use lukaszmakuch\Haringo\Builder\Extension\ValueSourceExtension;
use lukaszmakuch\Haringo\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\MethodSelectorMap;
use lukaszmakuch\Haringo\Haringo;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;

/**
 * Allows to build an object builder.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface HaringoBuilder
{
    /**
     * Builds an object builder.
     * 
     * @return Haringo
     */
    public function build();
    
    /**
     * Sets a map of parameter selectors.
     * 
     * @param ParamSelectorMap $map
     */
    public function setParamSelectorMap(ParamSelectorMap $map);
    
    /**
     * Sets a map of full class path sources.
     * 
     * @param ClassPathSourceMap $map
     */
    public function setClassSourceMap(ClassPathSourceMap $map);
    
    /**
     * Sets a map of method selectors.
     * 
     * @param MethodSelectorMap $map
     */
    public function setMethodSelectorMap(MethodSelectorMap $map);
    
    /**
     * Adds support of a new value source.
     * 
     * @param ValueSourceExtension $extension
     */
    public function addValueSourceExtension(ValueSourceExtension $extension);
}
