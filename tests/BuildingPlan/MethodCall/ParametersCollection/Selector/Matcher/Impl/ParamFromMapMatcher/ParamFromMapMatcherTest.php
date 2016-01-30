<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ParamSelector\Matcher\Impl\ParamFromMapMatcher;

use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\BuildPlan\FullClassPathSource\Resolver\Impl\ExactClassPathResolver;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamFromMap;
use lukaszmakuch\ObjectBuilder\ParamSelector\Matcher\Impl\ParamByExactNameMatcher;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Impl\ExactMethodName;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\Impl\ExactMethodNameMatcher;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\Selector\Matcher\Impl\MethodSelectorFromMap\FullMethodIdentifier;
use lukaszmakuch\ObjectBuilder\TestClass;
use PHPUnit_Framework_TestCase;
use ReflectionParameter;

class ParamFromMapMatcherTest extends PHPUnit_Framework_TestCase
{
    public function testCorrectMapping()
    {
        $map = new ParamSelectorMap();
        $map->addSelector(
            "param_selector_key",
            new FullParamIdentifier(
                new FullMethodIdentifier(
                    new ExactClassPath(TestClass::class),
                    new ExactMethodName("setMembers")
                ),
                new ParamByExactName("newA")
            )
        );
        
        $matcher = new ParamFromMapMatcher(
            $map,
            new ExactClassPathResolver(),
            new ExactMethodNameMatcher(),
            new ParamByExactNameMatcher()
        );
        
        $newAParam = new ReflectionParameter([TestClass::class, "setMembers"], "newA");
        $newAParamSelectorFromMap = new ParamFromMap("param_selector_key");
        $this->assertTrue($matcher->parameterMatches(
            $newAParam,
            $newAParamSelectorFromMap
        ));
    }
}
