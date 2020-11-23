<?php

declare(strict_types=1);

namespace Owl;

function str_has_tags(string $str): bool
{
    return is_string($str)
    && strlen($str) > 2
    && $str !== strip_tags($str);
}

function array_set_in(array &$target, array $path, mixed $value, $push = false): void
{
    $last_key = array_pop($path);

    foreach ($path as $key) {
        if (!array_key_exists($key, $target)) {
            $target[$key] = [];
        }

        $target = &$target[$key];

        if (!is_array($target)) {
            throw new \RuntimeException('Cannot use a scalar value as an array');
        }
    }

    if ($push) {
        if (!array_key_exists($last_key, $target)) {
            $target[$last_key] = [];
        } elseif (!is_array($target[$last_key])) {
            throw new \RuntimeException('Cannot use a scalar value as an array');
        }

        array_push($target[$last_key], $value);
    } else {
        $target[$last_key] = $value;
    }
}

/**
 * the difference between array_set_in and array_push_in:
 * array_set_in:
 *      $target[$path] = $value;.
 * array_push_in:
 *      $target[$path][] = $value;
 *
 * @param array $target
 * @param array $path
 * @param mixed $value
 *
 * @return void
 */
function array_push_in(array &$target, array $path, mixed $value): void
{
    array_set_in($target, $path, $value, true);
}

function array_get_in(array $target, array $path)
{
    foreach ($path as $key) {
        if (!isset($target[$key])) {
            return false;
        }

        $target = &$target[$key];
    }

    return $target;
}

function array_unset_in(array &$target, array $path): void
{
    $last_key = array_pop($path);

    foreach ($path as $key) {
        if (!is_array($target)) {
            return;
        }

        if (!array_key_exists($key, $target)) {
            return;
        }

        $target = &$target[$key];
    }

    unset($target[$last_key]);
}

/**
 * @example
 * $value = [
 *     'a' => [
 *         'b' => [],
 *     ],
 *     'c' => [
 *         'd' => [
 *             'e' => 1,
 *         ],
 *     ],
 * ];
 *
 * // [
 * //     'c' => [
 * //         'd' => [
 * //             'e' => 1,
 * //         ],
 * //     ],
 * // ];
 * $value = \Owl\array_trim($value);
 *
 * @param array $target
 *
 * @return array
 */
function array_trim(array $target): array
{
    $keys = array_keys($target);
    $is_array = ($keys === array_keys($keys));

    $result = [];

    foreach ($target as $key => $value) {
        if (is_array($value) && $value) {
            $value = array_trim($value);
        }

        if ($value === null || $value === '' || $value === []) {
            continue;
        }

        $result[$key] = $value;
    }

    if ($is_array && $result) {
        $result = array_values($result);
    }

    return $result;
}

function safe_json_encode($value, $options = 0, $depth = 512)
{
    return json_encode($value, $options | JSON_THROW_ON_ERROR, $depth);
}

function safe_json_decode($json, $assoc = false, $depth = 512, $options = 0)
{
    return json_decode(strval($json), $assoc, $depth, $options | JSON_THROW_ON_ERROR);
}
