<?php
class _
{
    public function curry(callable $fn, ...$start)
    {
        return function (...$args) use ($fn, $start) {
            return @$fn(...array_map(function ($v) use (&$args) {
                if (is_a($v, '_')) {
                    return array_shift($args);
                }
                return $v;
            }, $start));
        };
    }
    public function compose(...$fns)
    {
        $prev = array_shift($fns);
        foreach ($fns as $fn) {
            $prev = function (...$args) use ($fn, $prev) {
                return $prev($fn(...$args));
            };
        }
        return $prev;
    }
    public function filter($fn, $data)
    {
        foreach ($data as $k => $v) {
            if (!$fn($v, $k, $data)) {
                unset($data[$k]);
            }
        }
        return $data;
    }
    public function map($fn, ...$data)
    {
        $first = array_shift($data);
        return array_map(function ($val, $key, ...$data) use ($fn, $first) {
            return $fn($val, $key, $first, ...$data);
        }, $first, array_keys($first), ...$data);
    }
    public function reduce($fn, $data, $init = null)
    {
        return array_reduce(array_keys($data), function ($carry, $key) use ($fn, $data) {
            return $fn($carry, $data[$key], $key, $data);
        }, $init);
    }
}
