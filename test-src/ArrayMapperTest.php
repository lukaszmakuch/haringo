<?php

/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder;

abstract class ArrayMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Asserts that the array contains only:
     *   - scalar values
     *   - NULLs
     *   - arrays of these
     * 
     * @param array $mappedArray
     */
    protected function assertAllowedDataTypesWithin(array $mappedArray)
    {
        array_walk_recursive($mappedArray, function ($value) {
            $this->assertTrue(
                is_scalar($value)
                || (null === $value)
            );
        });
    }
}