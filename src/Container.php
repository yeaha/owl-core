<?php

declare(strict_types=1);

namespace Owl;

use Psr\Container\ContainerInterface;

/**
 * 依赖注入容器.
 *
 * @example
 *
 * $container = new \Owl\Container;
 *
 * $container->set('foo', function() {
 *     return 'bar';
 * });
 *
 * var_dump($container->get('foo') === 'bar');
 */
class Container implements ContainerInterface
{
    /**
     * 保存用 set 方法注册的回调方法.
     *
     * @var callable[]
     */
    protected array $callbacks = [];

    /**
     * 每个回调方法的执行结果会被缓存到这个数组里.
     *
     * @var mixed[]
     */
    protected array $values = [];

    /**
     * 从容器内获得注册的回调方法执行结果.
     *
     * 注意：
     * 注册的回调方法只会执行一次，即每次get都拿到同样的结果
     *
     * @inheritDoc
     */
    public function get($id)
    {
        if (isset($this->values[$id])) {
            return $this->values[$id];
        }

        $callback = $this->getCallback($id);
        $value = call_user_func($callback);
        $this->values[$id] = $value;

        return $value;
    }

    /**
     * 检查是否存在指定的注入内容.
     *
     * @inheritDoc
     */
    public function has($id)
    {
        return isset($this->values[$id]) || isset($this->callbacks[$id]);
    }

    /**
     * @param string  $id
     * @param callable $callback
     */
    public function set(string $id, callable $callback)
    {
        $this->callbacks[$id] = $callback;
    }

    /**
     * 删除容器内的成员，包括回调的执行结果.
     *
     * @param string $id
     *
     * @return void
     */
    public function remove(string $id): void
    {
        unset($this->callbacks[$id]);
        unset($this->values[$id]);
    }

    /**
     * 获得指定名字的回调函数.
     *
     * @param string $id
     *
     * @return callable
     *
     * @throws NotFoundException 指定的$id不存在时抛出错误
     */
    public function getCallback(string $id): callable
    {
        if ($this->has($id)) {
            return $this->callbacks[$id];
        }

        throw new NotFoundException(sprintf('"%s" does not exists in container', $id));
    }

    /**
     * 重置整个容器，清空内容.
     */
    public function reset()
    {
        $this->callbacks = [];
        $this->values = [];
    }

    /**
     * 刷新所有的执行结果.
     */
    public function refresh()
    {
        $this->values = [];
    }
}
