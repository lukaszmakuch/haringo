<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\Impl\MethodSelectorFromMap;

use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\ClassSource\Resolver\Impl\ExactClassPathResolver;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Impl\MethodSelectorFromMap;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\Impl\ExactMethodNameMatcher;
use lukaszmakuch\ObjectBuilder\TestClass;
use PHPUnit_Framework_TestCase;
use ReflectionMethod;

class MethodSelectorFromMapMatcherTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectMatch()
    {
        $key = "mapped_method_selector_key";
        $selector = new MethodSelectorFromMap($key);
        
        $actualClassSource = new ExactClassPath(TestClass::class);
        $actualMethodSelector = new ExactMethodName("setMembers");
        
        $map = new MethodSelectorMap();
        $map->addSelector(
            $key,
            new FullMethodIdentifier($actualClassSource, $actualMethodSelector)
        );
        
        $actualMethodMatcher = new ExactMethodNameMatcher();
        $classPathResolver = new ExactClassPathResolver();
        $matcher = new MethodSelectorFromMapMatcher(
            $map,
            $classPathResolver,
            $actualMethodMatcher
        );
        
        $testMethod = new ReflectionMethod(TestClass::class, "setMembers");
        $this->assertTrue($matcher->methodMatches($testMethod, $selector));
    }
}
