<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelector\Impl;

use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;


/**
 * Allows to select a param by it's position instead of name.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ParamByPosition implements ParameterSelector
{
    private $positionFrom0;
   
    /**
     * Sets which param (from left, from 0) should be selected.
     * 
     * @param int $positionFrom0
     */
    public function __construct($positionFrom0)
    {
        $this->positionFrom0 = $positionFrom0;
    }
    
    /**
     * Gets previously provided value.
     * 
     * @return int 
     */
    public function getParamPosFrom0()
    {
        return $this->positionFrom0;
    }
}
