# Underscore
2kb functional class for PHP includes 
`curry`
`compose`
`filter`
`map`
`reduce`
`get`


##Why use this

If you are learning functional programming then this functional helper class can help you use higher order functions such as map, filter, reduce and compose functions based on another smaller functions in order to re-use functions, To achieve this sometimes you need to pre-populate a function with some parameters already filled in, this is where currying comes in.


## Documentation


####Compose

Compose will return a callable function that is made up of all the functions passed to it, these will execute left to right, you use this function when you are looking to give semantic meaning to a combination of functions or you are re-using this particular set of functions (or transforms) a lot

Example 1 - a practical example, execute the echo only when the callback is called 
```php
$echo = function($val){
    echo $val;
};
$listFormatter = function($val){
    return '<li>'.$val.'</li>';
};
$echoList = (new _)->compose([$listFormatter,$echo]);

//later in my template
array_walk($data,$echoList); 
```


Example 2 - a semantic example, if we use salt and pepper a lot why not combine them into their own function?:
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
```
####Curry

####Filter

####Map

####Reduce

####Get

## Motivation

Implement the simplest functional tools for PHP possible to enable practical: 
`filter`, `map`, `reduce` operations

`filter`, `map`, `reduce` now mostly match their JavaScript counterparts where all callbacks receive the current value, key and complete array as parameters


>Currying is the key to making these tools practical consider the following code 

```php
$chooseMyPokemon = function ($type, $row) {
    return $row['level'] > 50 && $row['type'] == $type;
};

$pokemon = [
    ['level' => 66, 'name' => 'slowpoke', 'type' => 'water'],
    ['level' => 77, 'name' => 'arcanine', 'type' => 'fire'],
    ['level' => 32, 'name' => 'pikachu', 'type' => 'electric'],
];

$iLikeFire = (new _)->curry($chooseMyPokemon, 'fire', (new _));
$iChooseYou = (new _)->filter($iLikeFire, $pokemon);
var_dump($iChooseYou);

array(1) { [1]=> array(3) { ["level"]=> int(77) ["name"]=> string(8) "arcanine" ["type"]=> string(4) "fire" } }
```
    
  $chooseMyPokemon could not be used with filter without currying in the $type parameter ahead of time because there is a parameter mismatch
  
  The philosophy here is everything is data, filter, map, reduce are the tools for operating on data in a reuseable way, currying can help you squeeze your data into those tools

## TODO

1. Write documentation 
2. Add Docblock 
3. Re-write tests as PHP Unit tests 
4. Add composer support 
5. Add PHP module - maybe?
