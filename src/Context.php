<?php

declare(strict_types=1);

namespace Owl;

use Owl\Parameter\Validator;

abstract class Context
{
    protected array $config = [];

    private static array $registry = [];

    abstract public function set($key, $val): void;

    abstract public function get($key = null): mixed;

    abstract public function has($key): bool;

    abstract public function remove($key): void;

    abstract public function clear(): void;

    public function __construct(array $config)
    {
        (new Validator())->execute($config, ['token' => ['type' => 'string']]);

        $this->config = $config;
    }

    public function setConfig($key, $val): void
    {
        $this->config[$key] = $val;
    }

    public function getConfig($key = null): mixed
    {
        return (($key === null) ? $this->config : isset($this->config[$key])) ? $this->config[$key] : null;
    }

    public function getToken(): string
    {
        return $this->getConfig('token');
    }

    // 保存上下文数据，根据需要重载
    public function save(): void
    {
    }

    final public static function register(string $type, string $contextClass): void
    {
        $classes = class_parents($contextClass);
        if (!in_array(self::class, $classes)) {
            throw new \UnexpectedValueException('should be a subclass of \Owl\Context');
        }
        self::$registry[$type] = $contextClass;
    }

    public static function factory(string $type, array $config): static
    {
        $class = self::$registry[strtolower($type)] ?? null;
        if (!$class) {
            throw new \UnexpectedValueException('Unknown context handler type: ' . $type);
        }

        return new $class($config);
    }
}