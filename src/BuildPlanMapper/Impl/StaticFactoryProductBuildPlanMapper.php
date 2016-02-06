<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildPlanMapper\Impl;

use lukaszmakuch\Haringo\BuildPlan\Impl\StaticFactoryBuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\StaticFactoryProductBuildPlan;
use lukaszmakuch\Haringo\ClassSourceMapper\ClassSourceMapper;
use lukaszmakuch\Haringo\Mapper\SerializableArrayMapper;
use lukaszmakuch\Haringo\MethodCallMapper\MethodCallArrayMapper;

/**
 * Maps build plans of static factory method's products.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
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
