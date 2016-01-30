<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan;

use lukaszmakuch\ObjectBuilder\ClassSource\FullClassPathSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\MethodCall;
use PHPUnit_Framework_TestCase;

class BuildPlanTest extends PHPUnit_Framework_TestCase
{
    public function testHoldingData()
    {
        $classSource = $this->getMock(FullClassPathSource::class);
        $call = $this->getMockBuilder(MethodCall::class)
            ->disableOriginalConstructor()
            ->getMock();
        $process = new BuildPlan($classSource);
        $this->assertTrue($process->addMethodCall($call) === $process);
        $this->assertTrue($classSource == $process->getClassSource());
        $this->assertTrue($call === $process->getAllMethodCalls()[0]);
    }
    
    
}