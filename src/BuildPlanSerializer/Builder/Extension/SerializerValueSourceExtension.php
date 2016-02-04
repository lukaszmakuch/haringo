<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\BuildPlanSerializer\Builder\Extension;

/**
 * Provides all what's needed to serialize a new value source.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
interface SerializerValueSourceExtension
{
    /**
     * Gets mapper able to map the supported ValueSource implementation 
     * to and from an array.
     * 
     * @return SerializableArrayMapper
     */
    public function getMapper();
    
    /**
     * Gets a specified class of ValueSource objects supported by this extension.
     * 
     * @return String full class path
     */
    public function getSupportedValueSourceClass();
    
    /**
     * Gets unique (among all value source extensions of any vendor) 
     * identifier of this extension.
     * 
     * @return String like "vendor.object_builder.extension.my_fancy_extension"
     */
    public function getUniqueExtensionId();
}
