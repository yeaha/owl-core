<?php

declare(strict_types=1);

namespace Owl;

/**
 * @example
 * use Owl\Config;
 *
 * Config::merge([
 *     'foo' => [
 *         'bar' => [
 *             'baz' => 1,
 *         ]
 *     ]
 * ]);
 *
 * Config::get();
 * Config::get('foo');
 * Config::get('foo', 'bar');
 * Config::get('foo', 'bar', 'baz');
 * Config::get(['foo', 'bar', 'baz']);
 */
class Config
{
    private static array $config = [];

    public static function merge(array $config): void
    {
        self::$config = array_merge(self::$config, $config);
    }

    public static function get($keys = null): mixed
    {
        $keys = ($keys === null ? [] : is_array($keys)) ? $keys : func_get_args();

        try {
            return \Owl\array_get_in(self::$config, $keys);
        } catch (\RuntimeException $ex) {
            return false;
        }
    }
}
