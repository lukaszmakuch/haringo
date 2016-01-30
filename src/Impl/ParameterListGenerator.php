<?php
/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\MethodCall\ParametersCollection\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\ParameterMatcher;
use lukaszmakuch\ObjectBuilder\ValueSource\Resolver\ValueResolver;
use ReflectionMethod;

class ParameterListGenerator
{
    private $matcher;
    private $resolver;
    
    public function __construct(
        ParameterMatcher $m,
        ValueResolver $r
    ) {
        $this->matcher = $m;
        $this->resolver = $r;
    }
    
    /**
     * @param ReflectionMethod $reflectedMethod
     * @param AssignedParamValue[] $paramsWithSelectors
     * 
     * @return array ordered values ready to be passed as arguments
     */
    public function getOrderedListOfArgumentValue(
        ReflectionMethod $reflectedMethod, 
        array $paramsWithSelectors
    ) {
        $orderedList = [];
        $reflectedParams = $reflectedMethod->getParameters();
        $reflectedParamsCount = count($reflectedParams);
        for ($paramIndex = 0; $paramIndex < $reflectedParamsCount; $paramIndex++) {
            $orderedList[$paramIndex] = null;
            $this->findAndSetValue(
                $paramIndex,
                $orderedList,
                $paramsWithSelectors,
                $reflectedParams
            );
        }
        
        return $orderedList;
    }
    
    /**
     * @param int $paramIndex
     * @param array $listOfParams
     * @param array $allParamsWithSelectors
     */
    private function findAndSetValue(
        $paramIndex,
        &$listOfParams,
        array $allParamsWithSelectors,
        array $reflectedParams
    ) {
        foreach ($allParamsWithSelectors as $paramWithSelector) {
            if ($this->matcher->parameterMatches(
                $reflectedParams[$paramIndex],
                $paramWithSelector->getSelector()
            )) {
                $listOfParams[$paramIndex] = $this->resolver->resolveValueFrom(
                    $paramWithSelector->getValueSource()
                );
            }
        }
    }
}