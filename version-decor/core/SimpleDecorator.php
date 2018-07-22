<?php

namespace core;

use core\Exceptions\DecoratorException;

/**
 * Class SimpleDecorator
 * @package core
 */
class SimpleDecorator
{
    /**
     * Декорируемый объект
     * @var object
     */
    protected $object;

    /**
     * SimpleDecorator constructor.
     * @param array $arguments
     * @internal param $object
     * @throws DecoratorException
     */
    public function __construct(...$arguments)
    {
        $object = $arguments[0] ?? null;
        if (!\is_object($object)) {
            throw new DecoratorException('Декоратор ожидает декорируемый объект, получен: ' . gettype($object));
        }

        $this->object = $object;
    }

    /**
     * @param string $propertyName
     * @return mixed
     * @throws DecoratorException
     */
    public function __get($propertyName)
    {
        if (!property_exists($this->object, $propertyName)) {
            throw new DecoratorException("Свойство {$propertyName} не найдено в " . get_class($this->object));
        }

        return $this->object->{$propertyName};
    }

    /**
     * @param string $propertyName
     * @param string $propertyValue
     * @throws DecoratorException
     */
    public function __set($propertyName, $propertyValue)
    {
        if (!property_exists($this->object, $propertyName)) {
            throw new DecoratorException("Свойство {$propertyName} не найдено в " . get_class($this->object));
        }

        $this->object->{$propertyName} = $propertyValue;
    }

    /**
     * @param string $methodName
     * @param array $arguments
     * @return mixed
     * @throws DecoratorException
     */
    public function __call($methodName, $arguments = [])
    {
        if (!method_exists($this->object, $methodName)) {
            throw new DecoratorException("Метод {$methodName} не может быть вызван для " . get_class($this->object));
        }

        return \call_user_func_array([$this->object, $methodName], $arguments);
    }
}