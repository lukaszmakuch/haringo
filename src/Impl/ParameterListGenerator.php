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
        /* @var $methodParams \ReflectionParameter[] */
        $methodParams = $reflectedMethod->getParameters();
        $numberOfMethodParams = count($methodParams);
        
        if ($numberOfMethodParams > 0) { //for older php
            $orderedParamList = array_fill(0, $numberOfMethodParams, null);
        } else {
            $orderedParamList = [];
        }
        
        foreach ($paramsWithSelectors as $paramValue) {
            $nothingSet = true;
            for ($paramIndex = 0; $paramIndex < count($methodParams); $paramIndex++) {
                $reflectedParam = $methodParams[$paramIndex];
                if ($this->matcher->parameterMatches(
                    $reflectedParam,
                    $paramValue->getSelector()
                )) {
                    $nothingSet = false;
                    $orderedParamList[$paramIndex] = $this->resolver->resolveValueFrom($paramValue->getValueSource());
                }
            }

            if ($nothingSet) {
                throw new ImpossibleToFinishBuildPlan("param not set");
            }
        }
        
        
        return $orderedParamList;
    }
}
