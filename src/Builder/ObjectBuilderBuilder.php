<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Builder;

use lukaszmakuch\ObjectBuilder\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\ObjectBuilder\MethodSelectorMatcher\Impl\MethodSelectorFromMap\MethodSelectorMap;
use lukaszmakuch\ObjectBuilder\ObjectBuilder;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;

/**
 * Allows to build an object builder.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ObjectBuilderBuilder
{
    /**
     * Builds an object builder.
     * 
     * @return ObjectBuilder
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
}
