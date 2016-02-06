<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ParamSelector\Impl;

use lukaszmakuch\Haringo\ParamSelector\ParameterSelector;

/**
 * Allows to get a param by its exact name.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ParamByExactName implements ParameterSelector
{
    private $exactParameterName;
    
    /**
     * @param String $exactParameterName exact param name
     */
    public function __construct($exactParameterName)
    {
        $this->exactParameterName = $exactParameterName;
    }
    
    /**
     * @return String previously provided exact param name
     */
    public function getExactName()
    {
        return $this->exactParameterName;
    }
}
