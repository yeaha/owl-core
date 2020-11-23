<?php

declare(strict_types=1);

namespace Owl\Traits;

/**
 * @example
 *
 * class Foobar {
 *     use \Owl\Traits\Context;
 *
 *     public function __construct() {
 *         $this->setContextHandler(\Owl\Context::factory('cookie', $config));
 *     }
 * }
 *
 * $foobar = new Foobar;
 *
 * $foobar->setContext($key, $value);
 * $value = $foobar->getContext($key);
 */
trait Context
{
    protected ?\Owl\Context $contextHandler = null;

    public function setContext($key, $val): void
    {
        $this->getContextHandler(true)->set($key, $val);
    }

    public function getContext($key = null): mixed
    {
        return $this->getContextHandler(true)->get($key);
    }

    public function hasContext($key): bool
    {
        return $this->getContextHandler(true)->has($key);
    }

    public function removeContext($key): void
    {
        $this->getContextHandler(true)->remove($key);
    }

    public function clearContext(): void
    {
        $this->getContextHandler(true)->clear();
    }

    public function saveContext(): void
    {
        $this->getContextHandler(true)->save();
    }

    public function setContextHandler(\Owl\Context $handler): void
    {
        $this->contextHandler = $handler;
    }

    public function getContextHandler($throwException = false): ?\Owl\Context
    {
        if (!$this->contextHandler && $throwException) {
            throw new \RuntimeException('Please set context handler before use');
        }

        return $this->contextHandler ?: null;
    }
}
