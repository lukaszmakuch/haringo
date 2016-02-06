<?php
/**
 * This file is part of the Haringo library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\Haringo\BuildingStrategy\Impl;

use lukaszmakuch\Haringo\Exception\UnableToBuild;
use lukaszmakuch\Haringo\ParamSelectorMatcher\ParameterMatcher;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;
use lukaszmakuch\Haringo\ValueSource\ValueSource;
use lukaszmakuch\Haringo\ValueSourceResolver\Exception\ImpossibleToResolveValue;
use lukaszmakuch\Haringo\ValueSourceResolver\ValueResolver;
use ReflectionMethod;
use ReflectionParameter;

/**
 * Generates list of resolved parameters' values in the same
 * order as they are in the reflected method.
 * 
 * If some parameter selector doesn't match any actual parameter,
 * an expcetion is thrown.
 * 
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
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
     * @throws UnableToBuild
     */
    public function getOrderedListOfArgumentValue(
        ReflectionMethod $reflectedMethod,
        array $paramsWithSelectors
    ) {
        $orderedParamList = $this->getEmptyListOfParamsOf($reflectedMethod);
        foreach ($paramsWithSelectors as $paramValue) {
            $this->addParamValueToList($reflectedMethod, $paramValue, $orderedParamList);
        }
        
        return $orderedParamList;
    }
    
    /**
     * Puts resolved param value at proper position in the list. 
     * 
     * @param \ReflectionMethod $reflectedMethod
     * @param AssignedParamValue $paramValue
     * @param array $orderedParamList
     * 
     * @return null, it modifies the array passed as the second argument
     * @throws UnableToBuild 
     */
    private function addParamValueToList(
        \ReflectionMethod $reflectedMethod,
        AssignedParamValue $paramValue,
        &$orderedParamList
    ) {
        /* @var $methodParams ReflectionParameter[] */
        $methodParams = $reflectedMethod->getParameters();
        $nothingSet = true;
        for ($paramIndex = 0; $paramIndex < count($methodParams); $paramIndex++) {
            $reflectedParam = $methodParams[$paramIndex];
            if ($this->matcher->parameterMatches(
                $reflectedParam,
                $paramValue->getSelector()
            )) {
                $nothingSet = false;
                $valueSource = $paramValue->getValueSource();
                $orderedParamList[$paramIndex] = $this->resolve($valueSource);
            }
        }

        if ($nothingSet) {
            throw new UnableToBuild("param not set");
        }
    }
    
    /**
     * @param ReflectionMethod $method
     * 
     * @return null[] array of length equal to the number of method parameters
     */
    private function getEmptyListOfParamsOf(\ReflectionMethod $method)
    {
        $numberOfMethodParams = count($method->getParameters());
        if ($numberOfMethodParams > 0) { //for older php
            $orderedParamList = array_fill(0, $numberOfMethodParams, null);
        } else {
            $orderedParamList = [];
        }
        
        return $orderedParamList;
    }
    
    /**
     * Resolves some value.
     * 
     * @param ValueSource $valSrc
     * @return mixed resolved value
     * @throws UnableToBuild
     */
    private function resolve(ValueSource $valSrc)
    {
        try {
            return $this->resolver->resolveValueFrom($valSrc);
        } catch (ImpossibleToResolveValue $e) {
            throw new UnableToBuild("impossible to resolve a value");
        }
    }
}
