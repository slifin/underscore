<?php
//TODO rewrite as PHPUnit tests
class tests extends PHPUnit_Framework_TestCase {
	function test_curry() {
		$test = function ($val1, $val2, $val3, $val4) {
			return implode(' ', [$val1, $val2, $val3, $val4]);
		};
		$test2 = (new _)->curry($test, 'this', (new _), 'correct', (new _));
		$this->assertEquals('this is correct hooray', $test2('is', 'hooray'));
		//expected return 'this is correct hooray'
	}

	function test_compose() {
		$add2 = (new _)->compose(
			function ($val) {
				return ++$val;
			}, function ($val) {
				return ++$val;
			});
		$this->assertEquals(4, $add2(2));
		//expected return 4
	}

	function test_filter() {
		$this->assertEquals(['hello' => 'world'], (new _)->filter(function ($val, $key, $arr) {
			return ($key == 'hello');
		}, ['hello' => 'world', 'world' => 'hello']));
		//expected return ['hello'=>'world']
	}

	function test_map() {
		$this->assertEquals([0 => 'hello', 1 => 'world'], (new _)->map(function ($val, $key, $arr) {
			return $key;
		}, ['hello' => 1, 'world' => 2]));
		//expected return [0=>'hello',1=>'world']
	}
	function test_reduce() {
		$this->assertEquals(9, (new _)->reduce(function ($carry, $val, $key, $arr) {
			return $carry + ($val + $key);
		}, [1 => 1, 2 => 1, 3 => 1], 0));
		//expected return 9
	}
}