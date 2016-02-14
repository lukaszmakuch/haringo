# Haringo
Serialize the way how you create an object, not the object itself!

[![travis](https://travis-ci.org/lukaszmakuch/haringo.svg)](https://travis-ci.org/lukaszmakuch/haringo)

## What's Haringo?
Haringo is a libary that aims to solve main problems with classical serialization:
* lost references which cause presence of \_\_wakeup() methods which use global variables
* data corruption after moving or renaming classes of serialized objects

### How's that achieved?
#### No more __wakeups!
Haringo allows to describe how an object should be built and then serialize
that description instead of the created object.
Thanks to this fact, any object built based on the serialized build plan
 doesn't need to know anything about things like global registry of resources
or the current user session.
#### Freedom of renaming!
With Haringo you can make your serialized data totally free of things like:
* class paths
* method names
* parameter names

It's possible thanks to configurable map based sources and selectors.

## Table of contents
1. [Getting Haringo](#installation)
* [Building Haringo](#building-haringo)
    * [Haringo builder](#getting-the-builder)
    * [Getting the basic Haringo](#getting-a-basic-haringo-instance)
    * [Maps](#setting-maps)
    * [Extensions](#extensions)
* [Using Haringo](#using-haringo)
    * [Build plans](#build-plans)
        * [New instance](#newinstancebuildplan)
        * [Static factory method product](#staticfactoryproductbuildplan)
        * [Factory object product](#factoryobjectproductbuildplan)
        * [Builder object product](#builderobjectproductbuildplan)
    * [Class path sources](#class-path-sources)
        * [Exact class path](#exactclasspath)
        * [Class path from a map](#classpathfrommap)
    * [Method selectors](#method-selectors)
        * [Constructor selector](#constructorselector)
        * [Method by exact name](#methodbyexactname)
        * [Method from a map](#methodfrommap)
    * [Parameter selectors](#parameter-selectors)
        * [Parameter by position](#parambyposition)
        * [Parameter by exact name](#parambyexactname)
        * [Parameter from a map](#paramfrommap)
    * [Value sources](#value-sources)
        * [Scalar](#scalarvalue)
        * [Array](#arrayvalue)
        * [Build plan result](#buildplanresultvalue)
    * [Working with build plans](#working-with-build-plans)
        * [Building objects](#building-objects-based-on-build-plans)
        * [Serialization](#serialization-of-build-plans)
        * [Fetching build plans by objects](#fetching-build-plans-by-built-objects)
* [Extending Haringo](#extend-haringo)

## Installation
Use [composer](https://getcomposer.org) to get the latest version:
```
$ composer require lukaszmakuch/haringo
```

## Building Haringo
To build Haringo, you can use the default implementation
of the *\lukaszmakuch\Haringo\Builder\HaringoBuilder* interface.

### Getting the builder
```php
<?php
use lukaszmakuch\Haringo\Builder\Impl\HaringoBuilderImpl;

$haringoBuilder = new HaringoBuilderImpl();
```
### Getting a basic Haringo instance
If you're not going to somehow extend your Haringo instance,
you can directly call the *HaringoBuilder::build():Haringo* method.
```php
<?php
use lukaszmakuch\Haringo\Builder\HaringoBuilder;

/* @var $haringoBuilder HaringoBuilder */
$haringo = $haringoBuilder->build();
```

### Setting Maps
In order to use a *ClassPathFromMap*, *MethodFromMap* or a *ParamFromMap*,
you need to build Haringo with instances of maps you are able to configure.

#### ClassPathSourceMap

##### Creating the map
All you need to create a map of class path sources,
is to create a new *ClassPathSourceMap* instance.
```php
<?php
use lukaszmakuch\Haringo\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;

$map = new ClassPathSourceMap();
```

##### Adding the map to Haringo
The new map must be passed to the Haringo builder
before using it to build a new Haringo.
However, it may be configured later, as it's passed as a reference.
```php
<?php
use lukaszmakuch\Haringo\Builder\HaringoBuilder;
use lukaszmakuch\Haringo\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;

/* @var $haringoBuilder HaringoBuilder */
/* @var $map ClassPathSourceMap */
$haringoBuilder->setClassSourceMap($map);
```

#### MethodSelectorMap
All you need to create a map of class path sources,
is to create a new *MethodSelectorMap* instance.

##### Creating the map
```php
<?php
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\MethodSelectorMap;

$map = new MethodSelectorMap();
```

##### Adding the map to Haringo
The new map must be passed to the Haringo builder
before using it to build a new Haringo.
However, it may be configured later, as it's passed as a reference.

```php
<?php
use lukaszmakuch\Haringo\Builder\HaringoBuilder;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\MethodSelectorMap;

/* @var $haringoBuilder HaringoBuilder */
/* @var $map MethodSelectorMap */
$haringoBuilder->setMethodSelectorMap($map);
```

#### ParamSelectorMap
All you need to create a map of class path sources,
is to create a new *ParamSelectorMap* instance.

##### Creating the map
```php
<?php
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;

$map = new ParamSelectorMap();
```
##### Adding the map to Haringo
The new map must be passed to the Haringo builder
before using it to build a new Haringo.
However, it may be configured later, as it's passed as a reference.
```php
<?php
use lukaszmakuch\Haringo\Builder\HaringoBuilder;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;

/* @var $haringoBuilder HaringoBuilder */
/* @var $map ParamSelectorMap */
$haringoBuilder->setParamSelectorMap($map);
```

### Extensions
#### ValueSourceExtension
It's possible to add support of different *ValueSource* implementations.
Every extension must implement the *\lukaszmakuch\Haringo\Builder\Extension\ValueSourceExtension*
interface and must be passed to the Haringo builder before using it to build a new Haringo.
See the description of creating your own Haringo extension.
```php
<?php
use lukaszmakuch\Haringo\Builder\HaringoBuilder;
use lukaszmakuch\Haringo\Builder\Extension\ValueSourceExtension;

/* @var $haringoBuilder HaringoBuilder */
/* @var $extension ValueSourceExtension */
$haringoBuilder->addValueSourceExtension($extension);
```
## Using Haringo
### Build plans
Every build plan describes how to get an instance of some class.
For documenting (and testing) purposes let's use that simple class:
```php
<?php
namespace lukaszmakuch\Haringo;

class TestClass
{
    public $memberA;
    public $memberB;
    public $passedToConstructor;

    public function __construct($passedToConstructor = null)
    {
        $this->passedToConstructor = $passedToConstructor;
    }

    public function setMembers($newA = null, $newB = null)
    {
        $this->memberA = $newA;
        $this->memberB = $newB;
    }
}
```


#### NewInstanceBuildPlan
Describes how a new instance of some class should be built.
It takes a source of class to instantiate and optional method calls which
will be called on the new instance.

Code equal to the result of the following build plan:
```php
<?php
use lukaszmakuch\Haringo\TestClass;

$obj = new TestClass(constructorParam);
$obj->setMembers("firstParamVal", "secondParamVal");
```

##### Example build plan:
```php
<?php
use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;
use lukaszmakuch\Haringo\TestClass;

$plan = (new NewInstanceBuildPlan())
    ->setClassSource(new ExactClassPath(TestClass::class))
    ->addMethodCall(
        (new MethodCall(new MethodByExactName("setMembers")))
            ->assignParamValue(new AssignedParamValue(
                new ParamByExactName("newB"),
                new ScalarValue("secondParamVal")
            ))
            ->assignParamValue(new AssignedParamValue(
                new ParamByExactName("newA"),
                new ScalarValue("firstParamVal")
            ))
    )
    ->addMethodCall(
        (new MethodCall(new MethodByExactName("__construct")))
            ->assignParamValue(new AssignedParamValue(
                new ParamByExactName("passedToConstructor"),
                new ScalarValue("constructorParam")
            ))
    );
```

#### StaticFactoryProductBuildPlan
Describes how to get a product of a static factory method.
It requires two things:
1. class source of the factory
* method call of the static factory method which returns the product

Let's create a class which provides a static factory method:
```php
<?php
use lukaszmakuch\Haringo\TestClass;

class TestStaticFactory
{
    public static function getProduct($configValue)
    {
        return new TestClass($configValue);
    }
}
```

Code equal to the result of the following build plan:
```php
<?php

$obj = TestStaticFactory::getProduct("paramValue");

```

##### Example build plan:
```php
<?php
use lukaszmakuch\Haringo\BuildPlan\Impl\StaticFactoryProductBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;
use lukaszmakuch\Haringo\TestClass;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;

$plan = (new StaticFactoryProductBuildPlan())
    ->setFactoryClass(new ExactClassPath(TestStaticFactory::class))
    ->setFactoryMethodCall(
        (new MethodCall(new MethodByExactName("getProduct")))
            ->assignParamValue(new AssignedParamValue(
                new ParamByExactName("configValue"),
                new ScalarValue("paramValue")
            ))
    );
```

#### FactoryObjectProductBuildPlan
Describes how to use a factory object to get some product.
It requires two things:
1. value source which is resolved to a factory object
* method call which returns the product

Let's create a class which provides a factory method:
```php
<?php
use lukaszmakuch\Haringo\TestClass;

class TestFactoryClass
{
    public function getProduct($configValue)
    {
        return new TestClass($configValue);
    }
}
```

Code equal to the result of the following build plan:
```php
<?php

$factory = new TestFactoryClass();
$obj = $factory->getProduct("paramValue");

```

##### Example build plan:
```php
<?php
use lukaszmakuch\Haringo\BuildPlan\Impl\FactoryObjectProductBuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;
use lukaszmakuch\Haringo\TestClass;
use lukaszmakuch\Haringo\ValueSource\Impl\BuildPlanResultValue;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;

$plan = (new FactoryObjectProductBuildPlan())
    ->setFactoryObject(
        //build TestFactoryClass instance
        new BuildPlanResultValue((new NewInstanceBuildPlan())
            ->setClassSource(new ExactClassPath(TestFactoryClass::class)
        ))
    )
    ->setBuildMethodCall(
        (new MethodCall(new MethodByExactName("getProduct")))
            ->assignParamValue(new AssignedParamValue(
                new ParamByExactName("configValue"),
                new ScalarValue("paramValue")
            ))
    );
```

#### BuilderObjectProductBuildPlan
Describes how to use a builder object to get some product.
There are two mandatory elements of plan:
1. value source which is resolved to a builder object
* method call which returns the product
As it's a builder, it's also possible to call some methods which determine the further result state.

Let's create a builder:
```php
<?php
use lukaszmakuch\Haringo\TestClass;

class TestBuilder
{
    private $param;
    public function setConstructorParam($param) { $this->param = $param; }
    public function build()
    {
        return new TestClass($this->param);
    }
}
```

Code equal to the result of the following build plan:
```php
<?php

$builder = new TestBuilder();
$builder->setConstructorParam("paramValue");
$obj = $builder->build();
```

##### Example build plan:
```php
<?php
use lukaszmakuch\Haringo\BuildPlan\Impl\BuilderObjectProductBuildPlan;
use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\MethodCall\MethodCall;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;
use lukaszmakuch\Haringo\ParamValue\AssignedParamValue;
use lukaszmakuch\Haringo\TestClass;
use lukaszmakuch\Haringo\ValueSource\Impl\BuildPlanResultValue;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;

$plan = (new BuilderObjectProductBuildPlan())
    ->setBuilderSource(
        //build TestBuilder object
        new BuildPlanResultValue((new NewInstanceBuildPlan())
            ->setClassSource(new ExactClassPath(TestBuilder::class)
        ))
    )
    ->addSettingMethodCall(
        (new MethodCall(new MethodByExactName("setConstructorParam")))
            ->assignParamValue(new AssignedParamValue(
                new ParamByExactName("param"),
                new ScalarValue("paramValue")
            ))
    )
    ->setBuildMethodCall(
        (new MethodCall(new MethodByExactName("build")))
    );
```
### Class path sources

#### ExactClassPath
Represents a full class path of some class.
```php
<?php
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;

//source of the \DateTime class
$classSrc = new ExactClassPath(\DateTime::class);
```

#### ClassPathFromMap
Allows to assign any class path to a string key.

The key stay unchanged while you move or rename your class,
so a previously serialized build plan doesn't get out of date.

In order to Haringo be able to resolve a class path from a map,
it must be [built with the map](#setting-maps).

##### Adding a new path source the map
```php
<?php
use lukaszmakuch\Haringo\ClassSourceResolver\Impl\ClassPathFromMapResolver\ClassPathSourceMap;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;

/* @var $map ClassPathSourceMap */

//add the \DateTime class source under the "date_time" key
$map->addSource(
    //key within the map
    "date_time",
    //actual class path source
    new ExactClassPath(\DateTime::class)
);
```

##### Using a class path from the map
```php
<?php
use lukaszmakuch\Haringo\ClassSource\Impl\ClassPathFromMap;

//class source stored under the "date_time" key
$classSrc = new ClassPathFromMap("date_time");
```
### Method selectors

#### ConstructorSelector
Selects the constructor.
```php
<?php
use lukaszmakuch\Haringo\MethodSelector\Impl\ConstructorSelector;

//constructor of any class
$methodSelector = ConstructorSelector::getInstance();
```

#### MethodByExactName
Selects some method by it's exact name. It may be "\_\_constructor" as well,
but for convenience it's preferable to use the *ConstructorSelector*.

```php
<?php
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;

//method with name "myMethodName"
$methodSelector = new MethodByExactName("myMethodName");
```

#### MethodFromMap
Allows to assign any full method identifier to a string key.
A full method identifier is a full class path source together
with some method selector.

Because every full method identifier contains a class path source,
it's possible to have two mapped methods under the same key which will represent
different methods of different classes.

The key stays unchanged while you rename your method,
so a previously serialized build plan doesn't get out of date.

In order to Haringo be able to get a method selector from a map,
it must be [built with the map](#setting-maps).

##### Adding a new method selector
```php
<?php
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\MethodSelectorMap;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\FullMethodIdentifier;
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodByExactName;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;

/* @var $map MethodSelectorMap */

//add the \DateTime::modify method selector under the "date_time.modify" key
$map->addSelector(
    //key within the map
    "date_time.modify",
    //full method identifier
    new FullMethodIdentifier(
        //source of the class which contains this method
        new ExactClassPath(\DateTime::class),
        //actual method selector
        new MethodByExactName("modify")
    )
);
```

##### Using a method selector from the map
```php
<?php
use lukaszmakuch\Haringo\MethodSelector\Impl\MethodFromMap;

//method selector stored under the "date_time.modify" key
$methodSelector = new MethodFromMap("date_time.modify");
```
### Parameter selectors

#### ParamByPosition
Selects one parameter by it's position from left (from index 0).
```php
<?php
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByPosition;

//second parameter of some method
$paramSelector = new ParamByPosition(1);
```
#### ParamByExactName
Selects one parameter by it's exact name.
```php
<?php
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByExactName;

//parameter called "name"
$paramSelector = new ParamByExactName("name");
```

#### ParamFromMap
Allows to assign any full parameter identifier to a string key.
A full parameter identifier is a full class path source
together with some method selector and parameter selector.

Because every full parameter identifier contains a class path source and
a method selector, it's possible to have two mapped parameters under the same key
which will represent different parameters of different methods (even of different classes).

The key stays unchanged while you rename or move the parameter or method,
so a previously serialized build plan doesn't get out of date.

In order to Haringo be able to get a parameter selector from a map,
it must be [built with the map](#setting-maps).


##### Adding a new parameter selector
```php
<?php
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher\ParamSelectorMap;
use lukaszmakuch\Haringo\ParamSelectorMatcher\Impl\ParamFromMapMatcher\FullParamIdentifier;
use lukaszmakuch\Haringo\MethodSelectorMatcher\Impl\MethodFromMap\FullMethodIdentifier;
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamByPosition;

/* @var $map ParamSelectorMap */

//add the first parameter of the \DateTime::modify method
//under the "date_time.modify.first_param" key
$map->addSelector(
    //key of the mapped selector
    "date_time.modify.first_param",
    //full identifier of the parameter
    new FullParamIdentifier(
        //full identifier of the method
        new FullMethodIdentifier(
            //class source
            new ExactClassPath(\DateTime::class),
            //method selector
            new MethodByExactName("modify")
        ),
        //actual parameter selector
        new ParamByPosition(0)
    )
);
```

##### Using a parameter selector from the map
```php
<?php
use lukaszmakuch\Haringo\ParamSelector\Impl\ParamFromMap;

//parameter selector stored under the "date_time.modify.first_param" key
$paramSelector = new ParamFromMap("date_time.modify.first_param");
```

### Value sources
#### ScalarValue
Represents a value like: boolean, integer, float, string.
```php
<?php
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;

//string "Hello Haringo!"
$valueSource = new ScalarValue("Hello Haringo!");

//integer 42
$valueSource = new ScalarValue(42);

//float 36.6
$valueSource = new ScalarValue(36.6);

//boolean true
$valueSource = new ScalarValue(true);
```
#### ArrayValue
Represents an array of other *ValueSource* objects.
Other sources may also be *ArrayValue* objects.
Keys may be both integers and strings.
```php
<?php
use lukaszmakuch\Haringo\ValueSource\Impl\ArrayValue;
use lukaszmakuch\Haringo\ValueSource\Impl\ScalarValue;

//[123 => "one two three", "anotherKey" => true]
$valueSource = new ArrayValue();
$valueSource->addValue(123, new ScalarValue("one two three"));
$valueSource->addValue("anotherKey", new ScalarValue(true));
```

#### BuildPlanResultValue
Represents a value which is the result of building something based the given *BuildPlan* object.
It may be used in order to create a build plan of a complex composite.
```php
<?php
use lukaszmakuch\Haringo\BuildPlan\Impl\NewInstanceBuildPlan;
use lukaszmakuch\Haringo\ClassSource\Impl\ExactClassPath;
use lukaszmakuch\Haringo\ValueSource\Impl\BuildPlanResultValue;

//create a build plan of a new \DateTime
$plan = new NewInstanceBuildPlan();
$plan->setClassSource(new ExactClassPath(\DateTime::class));

//create a value source based on this plan
$valueSource = new BuildPlanResultValue($plan);
```
## Working with build plans
### Building objects based on build plans
To get the product, call the *buildObjectBasedOn* method with a *BuildPlan*:
```php
<?php
use lukaszmakuch\Haringo\Haringo;
use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\Exception\UnableToBuild;

/* @var $haringo Haringo */
/* @var $buildPlan BuildPlan */
try {
    $buitObject = $haringo->buildObjectBasedOn($buildPlan);
} catch (UnableToBuild $e) {
    //it was impossible to build an object based on the given build plan
}
```
### Serialization of build plans
There are two methods which allow to serialize and deserialize build plans:
* Haringo::serializeBuildPlan(BuildPlan): String
* Haringo::deserializeBuildPlan(String): BuildPlan

```php
<?php
use lukaszmakuch\Haringo\Haringo;
use lukaszmakuch\Haringo\BuildPlan\BuildPlan;
use lukaszmakuch\Haringo\Exception\UnableToDeserialize;
use lukaszmakuch\Haringo\Exception\UnableToSerialize;

/* @var $haringo Haringo */
/* @var $buildPlan BuildPlan */
try {
    $serializedBuildPlan = $haringo->serializeBuildPlan(buildPlan);
    $deserializedBuildPlan = $haringo->deserializeBuildPlan(serializedBuildPlan);
} catch (UnableToSerialize $e) {
    //it was impossible to serialize this build plan
} catch (UnableToDeserialize $e) {
    //it was impossible to deserialize this build plan
}
```
### Fetching build plans by built objects
It's possible to figure out what was the build plan used to built some object:
```php
<?php
use lukaszmakuch\Haringo\Haringo;
use lukaszmakuch\Haringo\Exception\BuildPlanNotFound;
use lukaszmakuch\Haringo\Exception\UnableToBuild;
use lukaszmakuch\Haringo\BuildPlan\BuildPlan;

/* @var $haringo Haringo */
/* @var $buildPlan BuildPlan */
try {
    $buitObject = $haringo->buildObjectBasedOn($buildPlan);
    $fetchedBuildPlan = $haringo->getBuildPlanUsedToBuild(buitObject);
} catch (UnableToBuild $e) {
    //it was impossible to build an object based on the given build plan
} catch (BuildPlanNotFound $e) {
    //it was impossible to fetch the build plan used to build the given object
}
```


## Extend Haringo
Haringo is easily extensible!

### ValueSourceExtension
It's possible to add support of a totally new *ValueSource*.
All what you need to do,
is to implement the *\lukaszmakuch\Haringo\Builder\Extension\ValueSourceExtension* interface
and add it to the *HaringoBuilder* as described in the *Building Haringo* chapter.
For more details, check documentation of thats interface.
