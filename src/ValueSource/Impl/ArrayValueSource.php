<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\ValueSource\Impl;

use lukaszmakuch\ObjectBuilder\ValueSource\ValueSource;

class ArrayValueSource implements ValueSource
{
    private $sourcesByKeys = [];
    
    /**
     * @param String|int $index
     * @param ValueSource $source
     */
    public function addValue($index, ValueSource $source)
    {
        $this->sourcesByKeys[$index] = $source;
    }
    
    /**
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
