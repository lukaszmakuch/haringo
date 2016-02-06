<?php

/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildPlanSerializer\Builder\Extension;

use lukaszmakuch\Haringo\ValueSourceMapper\ValueSourceArrayMapper;

/**
 * Default value source extension implementation.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class SerializerValueSourceExtensionImpl implements SerializerValueSourceExtension
{
    private $mapper;
    private $supportedClass;
    private $uniqId;
    
    /**
     * Provides dependencies of a serializer value source extension.
     * 
     * @param ValueSourceArrayMapper $mapper
     * @param String $supportedClass full class path of the supported ValueSource
     * @param String $uniqId unique extension id
     */
    public function __construct(
        ValueSourceArrayMapper $mapper,
        $supportedClass,
        $uniqId
    ) {
        $this->mapper = $mapper;
        $this->supportedClass = $supportedClass;
        $this->uniqId = $uniqId;
    }
    
    public function getMapper()
    {
        return $this->mapper;
    }
    
    public function getSupportedValueSourceClass()
    {
        return $this->supportedClass;
    }
    
    public function getUniqueExtensionId()
    {
        return $this->uniqId;
    }
}
