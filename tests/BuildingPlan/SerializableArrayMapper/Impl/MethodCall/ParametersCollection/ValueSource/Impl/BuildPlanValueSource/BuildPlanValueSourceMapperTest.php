<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\MethodCallMapper\ParametersCollection\ValueSource\Impl\BuildPlanValueSource;

use lukaszmakuch\ObjectBuilder\BuildPlan\BuildPlan;
use lukaszmakuch\ObjectBuilder\ValueSource\Impl\BuildPlanValueSource;
use lukaszmakuch\ObjectBuilder\Mapper\Impl\BuildPlanArrayMapper;
use PHPUnit_Framework_TestCase;

class BuildPlanValueSourceMapperTest extends \lukaszmakuch\ObjectBuilder\ArrayMapperTest
{
    public function testCorrectMapping()
    {
        $buildPlan = $this->getMockBuilder(BuildPlan::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buildPlanMappedToArray = ["build_plan"];
        
        $buildPlanMapper = $this->getMockBuilder(BuildPlanArrayMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $buildPlanMapper->method("mapToArray")->will($this->returnValueMap([
            [$buildPlan, $buildPlanMappedToArray]
        ]));
        
        
        $buildPlanMapper->method("mapToObject")->will($this->returnValueMap([
            [$buildPlanMappedToArray, $buildPlan]
        ]));

        $valueSource = new BuildPlanValueSource($buildPlan);
        $mapper = new BuildPlanValueSourceMapper($buildPlanMapper);
        $mappedValueSource = $mapper->mapToArray($valueSource);
        $this->assertAllowedDataTypesWithin($mappedValueSource);
        /* @var $rebuiltValueSource BuildPlanValueSource */
        $rebuiltValueSource = $mapper->mapToObject($mappedValueSource);
        $this->assertTrue($valueSource->getBuildPlan() === $rebuiltValueSource->getBuildPlan());
    }
}
