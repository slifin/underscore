<?php
class _{
	function curry(callable $fn,...$start){
		return function (...$args) use ($fn, $start){
			$apply = array_map(function($v) use(&$args){
				if (is_a($v,'_'))
					return array_shift($args);
				return $v;
			},$start);
			if ((new \ReflectionFunction($fn))->getNumberOfRequiredParameters()>count($apply))
				return $this->curry($fn,...$apply);
			return $fn(...$apply);
		};
	}
	function compose(...$fns){
		$prev = array_shift($fns);
		foreach ($fns as $fn){
			$prev = function(...$args) use ($fn, $prev){
				return $prev($fn(...$args));
			};
		}
		return $prev;
	}
	function filter($fn,$data){
		foreach($data as $k=>$v){
			if (!$fn($v,$k,$data))
				unset($data[$k]);
		}
		return $data;
	}
	function map($fn,...$data){
		$first = array_shift($data);
		return array_map(function($val,$key,...$data) use($fn,$first){
			return $fn($val,$key,$first,...$data);
		},$first,array_keys($first),...$data);
	}
	function pluck($key,$data){
		return array_column($data,$key);
	}
	function reduce($fn,$data,$init=NULL){
		return array_reduce(array_keys($data),function($carry,$key) use($fn,$data){
			return $fn($carry,$data[$key],$key,$data);
		},$init);
	}
}