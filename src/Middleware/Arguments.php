<?php

declare(strict_types=1);

namespace Owl\Middleware;

/**
 * @example
 * $arguments = new Arguments('a', 'b', 'c');
 *
 * echo $arguments[0]."\n";
 * echo $arguments[1]."\n";
 * echo $arguments[2]."\n";
 *
 * var_dump($arguments->toArray());
 */
class Arguments implements \ArrayAccess
{
    private array $arguments = [];

    public function __construct(/*$arguments1[, $arguments2[, ...]]*/)
    {
        $this->arguments = func_get_args();
    }

    public function toArray(): array
    {
        return $this->arguments;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->arguments[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->arguments[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->arguments[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->arguments[$offset]);
    }
}
