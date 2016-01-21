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
    
    public function setMemberA($newA)
    {
        $this->memberA = $newA;
    }
    
    public function setMemberB($newB)
    {
        $this->memberB = $newB;
    }
}
