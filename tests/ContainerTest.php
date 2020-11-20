<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class Base
{
}
class Foo
{
}
class User extends Base
{
}

class ContainerTest extends TestCase
{
    /**
     * @var \Owl\Container
     */
    protected $container;

    public function testGet()
    {
        $this->container->set('user1', function () {
            return new User();
        });

        $this->assertInstanceOf(User::class, $this->container->get('user1'));
        $this->assertInstanceOf(Base::class, $this->container->get('user1'));
        $this->assertNotInstanceOf(Foo::class, $this->container->get('user1'));
    }

    public function testGetCallback()
    {
        $this->container->set('user1', function () {
            return new User();
        });
        $this->assertInstanceOf('Closure', $this->container->getCallback('user1'));
    }

    public function testGetUndefinedMember()
    {
        $this->expectDeprecationMessageMatches('/does not exists/');

        $this->container->get('undefined key');
    }

    public function testHas()
    {
        $this->container->set('obj1', function () {
            return new class{};
        });
        $this->assertTrue($this->container->has('obj1'));
        $this->assertFalse($this->container->has('obj2'));
    }

    public function testRemove()
    {
        $this->container->set('a', function () {
            return new class{};
        });
        $this->assertTrue($this->container->remove('a'));
    }

    protected function setUp(): void
    {
        $this->container = new \Owl\Container();
    }
}
