<?php

declare(strict_types=1);

namespace Owl\Traits;

/**
 * Singleton 单例模式
 */
trait Singleton
{
    protected static array $__instances__ = [];

    protected function __construct()
    {
    }

    public function __clone()
    {
        throw new \Exception('Cloning ' . __CLASS__ . ' is not allowed');
    }

    public static function getInstance(): static
    {
        $class = get_called_class();

        if (!isset(static::$__instances__[$class])) {
            static::$__instances__[$class] = new static();
        }

        return static::$__instances__[$class];
    }

    public static function resetInstance(): void
    {
        $class = get_called_class();
        unset(static::$__instances__[$class]);
    }
}
