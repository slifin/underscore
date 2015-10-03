<?php
class tests{
	function curry(){
		$test = function($val1,$val2,$val3,$val4){
			return implode(' ',[1=>$val1,2=>$val2,3=>$val3,4=>$val4]);
		};
		$test2 = (new _)->curry($test,'this',(new _),'correct',(new _));
		return $test2('is','hooray');
		//expected return 'this is correct hooray'
	}

	function compose(){
		$add2 = (new _)->compose(
			function($val){
				return ++$val;
			},function($val){
				return ++$val;
			});
		return $add2(2);
		//expected return 4
	}

	function filter(){
		return (new _)->filter(function($val,$key,$arr){
			return ($key == 'hello');
		},['hello'=>'world','world'=>'hello']);
		//expected return ['hello'=>'world']
	}

	function map(){
		return (new _)->map(function($val,$key,$arr){
			return $key;
		},['hello'=>1,'world'=>2]);
		//expected return [0=>'hello',1=>'world']
	}
	function pluck(){
		return (new _)->pluck('name',[['name'=>'adrian','age'=>26]]);
		//expected return ['adrian']
	}
	function reduce(){
		return (new _)->reduce(function($carry,$val,$key,$arr){
			return $carry + ($val + $key);
		},[1=>1,2=>1,3=>1],0);
		//expected return 9
	}
}

$tests = new Tests;
// echo $tests->curry();
// echo $tests->compose();
// var_dump($tests->filter());
// var_dump($tests->map());
// var_dump($tests->pluck());
// echo $tests->reduce();