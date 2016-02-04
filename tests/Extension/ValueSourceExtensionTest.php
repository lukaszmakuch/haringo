<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Extension;

use lukaszmakuch\ObjectBuilder\Builder\Extension\ValueSourceExtension;
use lukaszmakuch\ObjectBuilder\Builder\Impl\ObjectBuilderBuilderImpl;
use lukaszmakuch\ObjectBuilder\BuilderTestTpl;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\ObjectBuilder\MethodCall\MethodCall;
use lukaszmakuch\ObjectBuilder\MethodSelector\Impl\ConstructorSelector;
use lukaszmakuch\ObjectBuilder\ParamSelector\Impl\ParamByPosition;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\TestClass;
use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\ValueSourceMapper\ValueSourceArrayMapper;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\ValueResolver;

class NewValueSource implements ValueSource
{
}

class ValueSourceExtensionTest extends BuilderTestTpl
{
    public function testAddingExtension()
    {
        $valueSource = new NewValueSource();
        
        $extension = $this->getExtension($valueSource, "abc");
        $builder = $this->buildExtendedBuilder($extension);
        
        $plan = new NewInstanceBuildPlan();
        $plan->setClassSource(new ExactClassPath(TestClass::class));
        $plan->addMethodCall(
            (new MethodCall(ConstructorSelector::getInstance()))
                ->assignParamValue(new AssignedParamValue(
                    new ParamByPosition(0),
                    $valueSource
                ))
        );
        
        $serializedPlan = $builder->serializeBuildPlan($plan);
        $deserializedPlan = $builder->deserializeBuildPlan($serializedPlan);

        /* @var $rebuiltObject TestClass */
        $rebuiltObject = $builder->buildObjectBasedOn($deserializedPlan);
        
        $this->assertEquals("abc", $rebuiltObject->passedToConstructor);
    }
    
    /**
     * Builds mocks of all needed objects.
     * 
     * @return ValueSourceExtension
     */
    private function getExtension(ValueSource $supportedValueSource, $resolvedValue)
    {
        $mapper = $this->getMock(ValueSourceArrayMapper::class);
        $mapper->method("mapToArray")->will($this->returnValueMap([
            [$supportedValueSource, ["a"]]
        ]));
        $mapper->method("mapToObject")->will($this->returnValueMap([
            [["a"], $supportedValueSource]
        ]));
        
        $resolver = $this->getMock(ValueResolver::class);
        $resolver->method("resolveValueFrom")->will($this->returnValueMap([
            [$supportedValueSource, $resolvedValue]
        ]));
        
        $ext = $this->getMock(ValueSourceExtension::class);
     
        $ext->method("getMapper")->will($this->returnValue($mapper));
        $ext->method("getResolver")->will($this->returnValue($resolver));
        $ext->method("getSupportedValueSourceClass")->will($this->returnValue(NewValueSource::class));
        $ext->method("getUniqueExtensionId")->will($this->returnValue("new_ext_id"));
        
        return $ext;
    }
    
    /**
     * @param ValueSourceExtension $extension
     * @return \lukaszmakuch\ObjectBuilder\ObjectBuilder
     */
    private function buildExtendedBuilder(ValueSourceExtension $extension)
    {
        $builder = new ObjectBuilderBuilderImpl();
        $builder->addValueSourceExtension($extension);
        return $builder->build();
    }
}
