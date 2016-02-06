<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\ClassSource;

use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\Exception\UnableToDeserialize;
use lukaszmakuch\Haringo\Exception\UnableToSerialize;
use lukaszmakuch\Haringo\HaringoTestTpl;

class ExceptionTest extends HaringoTestTpl
{
    public function testUnableToSerializaException()
    {
        $this->setExpectedException(UnableToSerialize::class);
        $unsupportedBuildPlan = $this->getMock(BuildPlan::class);
        $this->haringo->serializeBuildPlan($unsupportedBuildPlan);
    }

    public function testUnableToDeserializaException()
    {
        $this->setExpectedException(UnableToDeserialize::class);
        $this->haringo->deserializeBuildPlan("not a serialized build plan");
    }
}