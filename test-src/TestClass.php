<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder;

/**
 * Class used for test purposes.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class TestClass
{
    public $memberA;
    public $memberB;
    public $passedToConstructor;
    
    public function __construct($passedToConstructor = null)
    {
        $this->passedToConstructor = $passedToConstructor;
    }
    
    public function setMembers($newA = null, $newB = null)
    {
        $this->memberA = $newA;
        $this->memberB = $newB;
    }
}
