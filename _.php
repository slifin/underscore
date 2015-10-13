<?php
class _
{
    /**
     * Compose a series of functions together
     * @param  functions    $fns a series of functions
     * @return function        combined functions
     */
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
    /**
     * Pre populate function with parameters whilst leaving some to be filled in later
     * the _ object passed in as a paremeter will denote a parameter offset that can be
     * filled in later
     * @param  callable $fn     function to partially apply
     * @param  mixed    $start     first set of arguments
     * @return function         partially applied function
     */
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
    /**
     * Filter data elements by the return of the function provided
     * return true to keep the element in the array
     * return false to remove the element in the array
     * @param  callable $fn   evaluation function
     * @param  array          $data a list to itterate over
     * @return array          filtered array
     */
    public function filter(callable $fn, $data)
    {
        foreach ($data as $k => $v) {
            if (!$fn($v, $k, $data)) {
                unset($data[$k]);
            }
        }
        return $data;
    }
    /**
     * function for accessing object/array offsets without errors
     * @param  mixed  $base    object/array to access
     * @param  string $access  a string which represents the offset with delimitors
     * @param  string $delimit delimitor found in $access defaults to .
     * @return mixed  value retireved from $base via $offset
     */
    public function get($base, $access, $delimit = '.')
    {
        return $this->reduce(function ($carry, $val, $key, $arr) {
            return reset(array_values(array_filter([
                    (is_array($carry) && isset($carry[$val])) ? $carry[$val] : (new _),
                    (is_object($carry) && isset($carry->{$val})) ? $carry->{$val} : (new _),
                    (is_object($carry) && isset($carry::${$val})) ? $carry::${$val} : (new _),
                    (--$key==count($arr))?null:null
                    ],function($val){
                        return !is_a($val,'_');
                    })
                ));
        },explode($delimit, $access), $base);
    }
    /**
     * itterate over a list and change the elements in a list
     * based on the return from the provided function
     * @param  callable $fn   function that will return a new value for the current element in the given array
     * @param  array   $data one dimensional array
     * @return array   transform data
     */
    public function map(callable $fn, ...$data)
    {
        $first = array_shift($data);
        return array_map(function ($val, $key, ...$data) use ($fn, $first) {
            return $fn($val, $key, $first, ...$data);
        }, $first, array_keys($first), ...$data);
    }
    /**
     * itterate over a list calling the provided function on the value of each call
     * on every itteration a carrying variable is put into the function which contains 
     * any previously returned values or $init
     * @param  callable $fn   user defined function for reducing
     * @param  array    $data user data to itterate over
     * @param  mixed    $init first value inserted into $carry
     * @return mixed    returned carry value
     */
    public function reduce(callable $fn, $data, $init = null)
    {
        return array_reduce(array_keys($data), function ($carry, $key) use ($fn, $data) {
            return $fn($carry, $data[$key], $key, $data);
        }, $init);
    }

}
