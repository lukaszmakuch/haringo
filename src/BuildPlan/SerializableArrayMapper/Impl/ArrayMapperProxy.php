<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Impl;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use lukaszmakuch\ClassBasedRegistry\Exception\ValueNotFound;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\Exception\ImpossibleToMapObject;
use lukaszmakuch\ObjectBuilder\BuildPlan\SerializableArrayMapper\SerializableArrayMapper;

class ArrayMapperProxy implements SerializableArrayMapper
{
    private static $MAPPED_KEY_MAPPER_UNIQ_ID = 1;
    private static $MAPPED_KEY_DATA = 2;
    
    private $mapperByObject;
    private $mapperByUniqId;
    private $uniqIdByObject;
    private $classOfSupportedMappedObjects;
    
    public function __construct(
        $classOfSupportedMappedObjects
    ) {
        $this->classOfSupportedMappedObjects = $classOfSupportedMappedObjects;
        $this->mapperByObject = new ClassBasedRegistry();
        $this->mapperByUniqId = [];
        $this->uniqIdByObject = new ClassBasedRegistry();
    }
    
    public function registerActualMapper(
        SerializableArrayMapper $actualMapper,
        $supportedClassOfMappedObjects,
        $registeredMapperUniqId
    ) {
        $this->mapperByObject->associateValueWithClasses(
            $actualMapper, 
            [$supportedClassOfMappedObjects]
        );
        $this->uniqIdByObject->associateValueWithClasses(
            $registeredMapperUniqId, 
            [$supportedClassOfMappedObjects]
        );
        $this->mapperByUniqId[$registeredMapperUniqId] = $actualMapper;
    }
    
    public function mapToArray($objectToMap)
    {
        $this->throwExceptionIfUnsupportedObject($objectToMap);
        return [
            self::$MAPPED_KEY_MAPPER_UNIQ_ID => $this->getMapperUniqIdBy($objectToMap),
            self::$MAPPED_KEY_DATA => $this->mapToArrayBySuitableMapper($objectToMap)
        ];
    }

    public function mapToObject(array $previouslyMappedObject)
    {
        $mapperUniqId = $previouslyMappedObject[self::$MAPPED_KEY_MAPPER_UNIQ_ID];
        $mapper = $this->getMapperByUniqId($mapperUniqId);
        $mappedData = $previouslyMappedObject[self::$MAPPED_KEY_DATA];
        return $mapper->mapToObject($mappedData);
    }

    private function throwExceptionIfUnsupportedObject($object)
    {
        if (false === ($object instanceof $this->classOfSupportedMappedObjects)) {
            throw new ImpossibleToMapObject();
        }

        try {
            $this->getMapperBy($object);
        } catch (ValueNotFound $e) {
            throw new ImpossibleToMapObject();
        }
    }
    
    /**
     * @return String
     */
    private function getMapperUniqIdBy($object)
    {
        return $this->uniqIdByObject->fetchValueByObjects([$object]);
    }
    
    private function mapToArrayBySuitableMapper($object)
    {
        /* @var $actualMapper SerializableArrayMapper */
        $actualMapper = $this->getMapperBy($object);
        return $actualMapper->mapToArray($object);
    }
    
    private function getMapperBy($object)
    {
        return $this->mapperByObject->fetchValueByObjects([$object]);
    }
    
    /**
     * @param String $uniqId
     * 
     * @return SerializableArrayMapper
     */
    private function getMapperByUniqId($uniqId)
    {
        return $this->mapperByUniqId[$uniqId];
    }
}
