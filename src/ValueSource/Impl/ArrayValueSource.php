<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSource\Impl;

use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

/**
 * Allows to build a structure where some value sources are stored under
 * some key or integer keys.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ArrayValueSource implements ValueSource
{
    private $sourcesByKeys = [];
    
    /**
     * Adds a value source under some key.
     * 
     * @param String|int $index
     * @param ValueSource $source
     */
    public function addValue($index, ValueSource $source)
    {
        $this->sourcesByKeys[$index] = $source;
    }
    
    /**
     * Gets all previously added value sources.
     * 
     * @return array like:
     * [
     *     String|int => ValueSource
     * ]
     */
    public function getAllSources()
    {
        return $this->sourcesByKeys;
    }
}
