<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelector\Impl;

use lukaszmakuch\ObjectBuilder\ParamSelector\ParameterSelector;

class ParamByPosition implements ParameterSelector
{
    private $positionFrom0;
    
    public function __construct($positionFrom0)
    {
        $this->positionFrom0 = $positionFrom0;
    }
    
    public function getParamPosFrom0()
    {
        return $this->positionFrom0;
    }
}
