<?php

declare(strict_types=1);

namespace Tests\Traits;

use Owl\Traits\Singleton;
use PHPUnit\Framework\TestCase;

class SingletonTest extends TestCase
{
    public function testSingletonInstance()
    {
        $foo1 = SingletonFoo::getInstance();
        $foo2 = SingletonFoo::getInstance();

        $this->assertTrue($foo1 === $foo2);
    }
}

class SingletonFoo
{
    use Singleton;

}
