<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\ValueSource\Impl;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ObjectBuilder\BuildPlan\MethodCall\ParametersCollection\ValueSource\ValueSource;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl\MethodCall\ParametersCollection\ValueSource\ValueSourceArrayMapper;

class ValueSourceMapperProxy implements ValueSourceArrayMapper
{
    private static $MAPPED_KEY_MAPPER_UNIQ_ID = 1;
    private static $MAPPED_KEY_DATA = 2;
    
    private $mapperByValSource;
    private $mapperByUniqId;
    private $uniqIdByValSource;
    
    public function __construct()
    {
        $this->mapperByValSource = new ClassBasedRegistry();
        $this->mapperByUniqId = [];
        $this->uniqIdByValSource = new ClassBasedRegistry();
    }
    
    public function registerActualMapper(
        ValueSourceArrayMapper $actualMapper,
        $supportedValueSourceClass,
        $registeredMapperUniqId
    ) {
        $this->mapperByValSource->associateValueWithClasses(
            $actualMapper, 
            [$supportedValueSourceClass]
        );
        $this->uniqIdByValSource->associateValueWithClasses(
            $registeredMapperUniqId, 
            [$supportedValueSourceClass]
        );
        $this->mapperByUniqId[$registeredMapperUniqId] = $actualMapper;
    }
    
    public function mapToArray($objectToMap)
    {
        return [
            self::$MAPPED_KEY_MAPPER_UNIQ_ID => $this->getMapperUniqIdBy($objectToMap),
            self::$MAPPED_KEY_DATA => $this->mapToArrayImpl($objectToMap)
        ];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        $mapperUniqId = $previouslyMappedObject[self::$MAPPED_KEY_MAPPER_UNIQ_ID];
        $mapper = $this->getMapperByUniqId($mapperUniqId);
        $mappedData = $previouslyMappedObject[self::$MAPPED_KEY_DATA];
        return $mapper->mapToObject($mappedData);
    }

    /**
     * @return String
     */
    private function getMapperUniqIdBy(ValueSource $s)
    {
        return $this->uniqIdByValSource->fetchValueByObjects([$s]);
    }
    
    private function mapToArrayImpl(ValueSource $s)
    {
        /* @var $actualMapper ValueSourceArrayMapper */
        $actualMapper = $this->mapperByValSource->fetchValueByObjects([$s]);
        return $actualMapper->mapToArray($s);
    }
    
    /**
     * @param String $uniqId
     * 
     * @return ValueSourceArrayMapper
     */
    private function getMapperByUniqId($uniqId)
    {
        return $this->mapperByUniqId[$uniqId];
    }
}
