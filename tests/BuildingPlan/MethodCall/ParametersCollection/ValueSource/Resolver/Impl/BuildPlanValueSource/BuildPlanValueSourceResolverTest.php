<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Resolver\Impl\BuildPlanValueSource;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\Impl\BuildPlanValueSource;
use lukaszmakuch\ObjectBuilder\ObjectBuilder;
use PHPUnit_Framework_TestCase;
use stdClass;

class BuildPlanValueSourceResolverTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectResolving()
    {
        $buildPlan = $this->getMockBuilder(BuildPlan::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $buildPlanResult = new stdClass();
        
        $objectBuilder = $this->getMock(ObjectBuilder::class);
        $objectBuilder
            ->method("buildObjectBasedOn")
            ->will($this->returnValueMap([
                [$buildPlan, $buildPlanResult]
            ]));
        
        $resolver = new BuildPlanValueSourceResolver($objectBuilder);
        $source = new BuildPlanValueSource($buildPlan);
        $resolvedValue = $resolver->resolveValueFrom($source);
        $this->assertTrue($resolvedValue === $buildPlanResult);
    }
}