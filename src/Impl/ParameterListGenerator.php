<?php
/**
 * This file is part of the ObjectBuilder library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ObjectBuilder\Impl;

use lukaszmakuch\ObjectBuilder\Exception\ImpossibleToFinishBuildPlan;
use lukaszmakuch\ObjectBuilder\ParamSelectorMatcher\ParameterMatcher;
use lukaszmakuch\ObjectBuilder\ParamValue\AssignedParamValue;
use lukaszmakuch\ObjectBuilder\ValueSourceResolver\ValueResolver;
use ReflectionMethod;

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
     * @throws ImpossibleToFinishBuildPlan when a selector hasn't been used
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
     * @throws ImpossibleToFinishBuildPlan when a selector hasn't been used
     */
    private function addParamValueToList(
        \ReflectionMethod $reflectedMethod,
        AssignedParamValue $paramValue,
        &$orderedParamList
    ) {
        /* @var $methodParams \ReflectionParameter[] */
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
                $orderedParamList[$paramIndex] = $this->resolver
                    ->resolveValueFrom($valueSource);
            }
        }

        if ($nothingSet) {
            throw new ImpossibleToFinishBuildPlan("param not set");
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
}
