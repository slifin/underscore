# _ ***.php***
3kB Functional class for PHP includes 
`curry`
`compose`
`filter`
`map`
`reduce`
`get`


##Why use this

If you are learning functional programming then this functional helper class can help you use higher order functions such as map, filter, reduce the cornerstones of data manipulation. To do this pratically, pre-populate your functions with some parameters already filled in, this can be achieved with [currying](#curry).


## Documentation


####Compose 
```php
/**
* Compose a series of functions together
* @param  functions    $fns a series of functions
* @return function        combined functions
*/
```

Compose will return a callable function that is made up of all the functions passed to it, these will execute left to right, use this function when you are looking to give semantic meaning to a combination of functions or you are re-using this particular set of functions (or transforms) a lot

#####Example 1
A practical example 
```php
$echo = function($val){
    echo $val;
};
$listFormatter = function($val){
    return '<li>'.$val.'</li>';
};
$echoList = (new _)->compose([$listFormatter,$echo]);

//later in my template
array_walk($myList,$echoList); 
```
Execute the echo only when the callback is called whilst leaving the original $listFormatter functionally clean without the side effect of printing to the page so now we could use $listFormatter in other non echoing contexts 

#####Example 2 
A semantic example 
```php
$salt = function($val){
    $val[] = 'salt';
    return $val;
}
$pepper = function($val){
    $val[] = 'pepper';
    return $val;
}
$addSaltAndPepper = (new _)->compose([$salt,$pepper]);
$meal = ['fish','chips'];
$completeMeal = $addSaltAndPepper($meal);
//$completedMeal = ['fish','chips','salt','pepper'];
```
We use salt and pepper all the time we may aswell make a third function that is a composite of salt and pepper
####Curry
```php
/**
 * Pre populate function with parameters whilst leaving some to be filled in later
 * the _ object passed in as a paremeter will denote a parameter offset that can be
 * filled in later
 * @param  callable $fn     function to partially apply
 * @param  mixed    $start  first set of arguments
 * @return function         partially applied function
 */
```
Currying allows you to partially apply the arguments of a function and get the resulting function back, allowing you to mold a function to fit inside a higher order function (a higher order function is one that can accept a function as a parameter - common examples are: filter, map, reduce)

#####Example 1
A practical example 
```php
$data = [
	['age' => 17, 'name' => 'Tom'],
	['age' => 35, 'name' => 'James'],
	['age' => 21, 'name' => 'Jon'],
];
$compareAge = function ($row, $comparison) {
	return $row['age'] == $comparison;
};
$compare = (new _)->curry($compareAge, (new _), rand(15, 50));
$filtered = array_filter($data, $compare);
```
Inside the $compareAge function $row is populated on each itteration of array_filter with the value inside $data, but $comparison is filled in once ahead of time because it has been curried in

#####Example 1
A semantic example
```php
$data = [
	['age' => 17, 'name' => 'Tom'],
	['age' => 35, 'name' => 'James'],
	['age' => 21, 'name' => 'Jon'],
];
$find = function ($row, $age, $name) {
	return $row['age'] == $age && $row['name'] == $name;
};
$findTom = (new _)->curry($find, (new _), 17, 'Tom');
$findJames = (new _)->curry($find, (new _), 35, 'James');
$Tom = array_filter($data, $findTom);
$James = array_filter($data, $findJames);
```
$findTom and $findJames are functions derived from a base function and have their own semantic meaning, shared logic 
only needs to be written once and currying allows both new functions to move through array_filter without issue

####Filter
```php
/**
 * Filter data elements by the return of the function provided
 * return true to keep the element in the array
 * return false to remove the element in the array
 * @param  callable $fn   evaluation function
 * @param  array          $data a list to itterate over
 * @return array          filtered array
*/
```
The filter() method creates a new array with all elements that pass the test implemented by the provided function.
####Map
```php
/**
 * itterate over a list and change the elements in a list
 * based on the return from the provided function
 * @param  callable $fn   function that will return a new value for the current element in the given array
 * @param  array   $data one dimensional array
 * @return array   transform data
 */
```
The map() method creates a new array with the results of calling a provided function on every element in this array.
####Reduce
```php
/**
 * itterate over a list calling the provided function on the value of each call
 * on every itteration a carrying variable is put into the function which contains
 * any previously returned values or $init
 * @param  callable $fn   user defined function for reducing
 * @param  array    $data user data to itterate over
 * @param  mixed    $init first value inserted into $carry
 * @return mixed    returned carry value
 */
 ```
The reduce() method applies a function against an accumulator and each value of the array (from left-to-right) to reduce it to a single value.
####Get
```php
/**
 * function for accessing object/array offsets without errors
 * @param  mixed  $base    object/array to access
 * @param  string $offset  a string which represents the php style offset
 * @param  string $default if the value isn't found return this value
 * @return mixed  value retireved from $base via $offset
 */
 ```
## Motivation

Implement the simplest functional tools for PHP possible to enable practical: 
`filter`, `map`, `reduce` operations
  
## Tests
>phpunit --bootstrap _.php tests.php

## TODO

1. Write documentation 
2. Add more tests to help assert intended behaviour and check for weaknesses
3. ~~Add Docblock~~ 
4. ~~Re-write tests as PHP Unit tests~~  
5. Add composer support 
6. Add PHP module - maybe?
