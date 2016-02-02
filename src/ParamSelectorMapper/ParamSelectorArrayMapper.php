<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelectorMapper;

use lukaszmakuch\ObjectBuilder\Mapper\SerializableArrayMapper;

/**
 * Represents interface of a mapper that shold be 
 * able to map some param selector to an array.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface ParamSelectorArrayMapper extends SerializableArrayMapper
{
}