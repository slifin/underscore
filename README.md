# Underscore
2kb functional library for PHP


`curry`
`compose`
`filter`
`map`
`pluck`
`reduce`

# Motivation

My motivation is to implement the simplest functional tools for PHP possible to enable practical: 
`filter`, `map`, `reduce` operations on your data 

`filter`, `map`, `reduce` now mostly match their JS counterparts where all callbacks receive the current value, key and complete array as parameters


Currying is the key to making these tools practical consider the following code 

    $chooseMyPokemon = function($row,$index,$resultset,$type){
    	return $row['level']>50&&$row['type']==$type;
    };
    
    $pokemon = [
    ['level'=>66,'name'=>'slowpoke','type'=>'water'],
    ['level'=>77,'name'=>'arcanine','type'=>'fire'],
    ['level'=>32,'name'=>'pikachu','type'=>'electric']
    ];
    
    
    $iLikeFire = (new _)->curry($chooseMyPokemon,(new _),(new _), (new _),'fire');
    $iChooseYou = (new _)->filter($iLikeFire,$pokemon);
    var_dump($iChooseYou);
    
  $chooseMyPokemon could not be used with filter without currying in the $type parameter ahead of time because there is a parameter mismatch
  
  The philosophy here is everything is data, filter, map, reduce are the tools for operating on data, currying can help you squeeze your data into those tools