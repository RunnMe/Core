7.0.4, 7.1.4, 7.2.4
===================
* Улучшена совместимость с PHP 7.1
* Обновлен идентификатор лицензии библиотеки

7.0.3, 7.1.3, 7.2.3
===================
* Добавлен строгий (без наследников) режим проверки типов в TypedCollection
* Вместо свойств $__notgetters и $__notsetters добавлены защищенные методы notgetters() и notsetters() 

7.0.2, 7.1.2, 7.2.2
===================
* Добавлен интерфейс InstanceableInterface и его эталонная реализация InstanceableTrait
* \#3 В ObjectAsArray добавлены свойства $__notgetters и $__notsetters и их использования для игнорирования геттеров и сеттеров
* Метод ObjectAsArrayInterface::isEmpty() переименован в empty()
* Добавлен интерфейс ConfigAwareInterface и его эталонная реализация ConfigAwareTrait
* Для классов, имеющих схему данных, добавлены HasSchemaInterface и HasSchemaTrait
* Добавлен класс ReflectionHelpers
* Исправление логики ObjectAsArrayTrait::needCasting()

7.0.1, 7.1.1, 7.2.1
===================
* Исправление ошибки с нулевыми ключами в Objects-as-Arrays

7.0.0, 7.1.0, 7.2.0
===================
* Первая релизная версия. Перенос кода из проекта Running.FM