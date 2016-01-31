<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlanMapper\Impl;

use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\StaticFactoryBuildPlan;
use lukaszmakuch\ObjectBuilder\BuildPlan\Impl\StaticFactoryProductBuildPlan;
use lukaszmakuch\ObjectBuilder\ClassSourceMapper\ClassSourceMapper;
use lukaszmakuch\ObjectBuilder\Mapper\SerializableArrayMapper;
use lukaszmakuch\ObjectBuilder\MethodCallMapper\MethodCallArrayMapper;

class StaticFactoryProductBuildPlanMapper  implements SerializableArrayMapper
{
    private static $MAPPED_INDEX_FACTORY_CLASS = 0;
    private static $MAPPED_INDEX_FACTORY_METHOD = 1;
    
    private $classSourceMapper;
    private $methodCallMapper;
    
    public function __construct(
        ClassSourceMapper $classSourceMapper,
        MethodCallArrayMapper $methodCallMapper
    ) {
        $this->classSourceMapper = $classSourceMapper;
        $this->methodCallMapper = $methodCallMapper;
    }
    
    public function mapToArray($objectToMap)
    {
        /* @var $objectToMap StaticFactoryBuildPlan */
        
        $factoryClass = $objectToMap->getFactoryClass();
        $mappedFactoryClass = $this->classSourceMapper
            ->mapToArray($factoryClass);
        
        $factoryMethodCall = $objectToMap->getFactoryMethodCall();
        $mappedFactoryMethodCall = $this->methodCallMapper
            ->mapToArray($factoryMethodCall);
        
        return [
            self::$MAPPED_INDEX_FACTORY_CLASS => $mappedFactoryClass,
            self::$MAPPED_INDEX_FACTORY_METHOD => $mappedFactoryMethodCall,
        ];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        $mappedFactoryClass = $previouslyMappedObject[
            self::$MAPPED_INDEX_FACTORY_CLASS
        ];
        $factoryClass = $this->classSourceMapper
            ->mapToObject($mappedFactoryClass);
        
        $mappedFactoryMethodCall = $previouslyMappedObject[
            self::$MAPPED_INDEX_FACTORY_METHOD
        ];
        $factoryMethodCall = $this->methodCallMapper
            ->mapToObject($mappedFactoryMethodCall);
                
        $buildPlan = new StaticFactoryProductBuildPlan();
        $buildPlan->setFactoryClass($factoryClass);
        $buildPlan->setFactoryMethodCall($factoryMethodCall);
        
        return $buildPlan;
    }
}
