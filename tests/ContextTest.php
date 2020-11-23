<?php

declare(strict_types=1);

namespace Tests;

use Owl\Context;
use Owl\Parameter\Exception;
use PHPUnit\Framework\TestCase;
use Tests\Mock\RedisService;

class FakeContext extends Context
{
    public function set($key, $val): void
    {
    }

    public function get($key = null): mixed
    {
    }

    public function clear(): void
    {
    }

    public function has($key): bool
    {
        return true;
    }

    public function remove($key): void
    {
    }
}

class NonContext
{
}

class ContextTest extends TestCase
{
    public function testGetRedisContext()
    {
        // 默认已注册redis类型的Context,所以无需register
        $context = Context::factory('redis', [
            'token' => 'test',
            'service' => new RedisService(),
        ]);
        $this->assertInstanceOf(Context\Redis::class, $context);
    }

    public function testRegister()
    {
        Context::register('fake', FakeContext::class);
        $context = Context::factory('fake', ['token' => 'test']);
        $this->assertInstanceOf(FakeContext::class, $context);
    }

    public function testErrorRegister()
    {
        // 注册非Context子类应该抛出异常
        $this->expectException(\UnexpectedValueException::class);
        Context::register('fake', NonContext::class);
    }

    public function testErrorFactory()
    {
        // 不提供token参数应该抛出异常
        $this->expectException(Exception::class);
        Context::register('fake', FakeContext::class);
        Context::factory('fake', []);
    }

    public function testNonExistingType()
    {
        // 未注册的类型应该抛出异常
        $this->expectException(\UnexpectedValueException::class);
        Context::factory('xxxx', ['token' => '123']);
    }
}