7.2.10, 7.3.10, 7.4.10
======================
* Container implementation refactoring 
* `Container::resolve()` method is added

7.2.9, 7.3.9, 7.4.9
===================
* Minor fix with `ReflectionType` 

7.2.8, 7.3.8, 7.4.8
===================
* Tests are using PHPUnit 8 framework now

7.2.7, 7.3.7, 7.4.7
===================
* Runn\Core\DateTime class is added

7.2.6, 7.3.6, 7.4.6
===================
* PHP 7.0 support is dropped
* PHP 7.1 support is dropped
* PHP 7.4 support is added

7.0.5, 7.1.5, 7.2.5
===================
* PSR DI Container is added

7.0.4, 7.1.4, 7.2.4
===================
* More PHP 7.1 compatibility
* License identifier update

7.0.3, 7.1.3, 7.2.3
===================
* Strict type check in TypedCollection is added
* notgetters() and notsetters() protected methods are added instead of $__notgetters and $__notsetters

7.0.2, 7.1.2, 7.2.2
===================
* InstanceableInterface and its implementation by InstanceableTrait are added
* \#3 $__notgetters and $__notsetters properties are added in ObjectAsArray for getters and setters ignore
* ObjectAsArrayInterface::isEmpty() method is renamed to empty()
* ConfigAwareInterface interface and its implementation by ConfigAwareTrait are added
* HasSchemaInterface and HasSchemaTrait are added for classes with data schema
* ReflectionHelpers class is added
* ObjectAsArrayTrait::needCasting() fix

7.0.1, 7.1.1, 7.2.1
===================
* Zero keys in Objects-as-Arrays fix

7.0.0, 7.1.0, 7.2.0
===================
* First released version. Code is transfered from Running.FM project