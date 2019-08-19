<?php

namespace App\Enums;

use ReflectionClass;

abstract class Enum
{
    /**
     * Constants cache
     *
     * @var array
     */
    protected static $constCacheArray = [];

    /**
     * Get all of the enum keys
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function getKeys(): array
    {
        return array_keys(self::getConstants());
    }

    /**
     * Get all of the enum values
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function getValues(): array
    {
        return array_values(self::getConstants());
    }

    /**
     * Get all of the constants on the class
     *
     * @return array
     * @throws \ReflectionException
     */
    protected static function getConstants(): array
    {
        $calledClass = get_called_class();

        if (! array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);

            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return self::$constCacheArray[$calledClass];
    }
}
