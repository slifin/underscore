<?php
class Complex {
	static function access() {
		return ['something' => (object) ['something' => 'found']];
	}
	function exploit(callable $func) {
		return $func();
	}
}
class Tests extends PHPUnit_Framework_TestCase {
	function testCurry() {
		$test = function ($val1, $val2, $val3, $val4) {
			return implode(' ', [$val1, $val2, $val3, $val4]);
		};
		$test2 = (new _)->curry($test, 'this', (new _), 'correct', (new _));
		$this->assertEquals('this is correct hooray', $test2('is', 'hooray'));
	}
	function testCompose() {
		$add2 = (new _)->compose(
			function ($val) {
				return ++$val;
			}, function ($val) {
				return ++$val;
			});
		$this->assertEquals(4, $add2(2));
	}
	function testFilter() {
		$this->assertEquals(['hello' => 'world'], (new _)->filter(function ($val, $key, $arr) {
			return ($key == 'hello');
		}, ['hello' => 'world', 'world' => 'hello']));
	}
	function testMap() {
		$this->assertEquals([0 => 'hello', 1 => 'world'], (new _)->map(function ($val, $key, $arr) {
			return $key;
		}, ['hello' => 1, 'world' => 2]));
	}
	function testReduce() {
		$this->assertEquals(9, (new _)->reduce(function ($carry, $val, $key, $arr) {
			return $carry + ($val + $key);
		}, [1 => 1, 2 => 1, 3 => 1], 0));
	}
	function testGetArrayRetrieval() {
		$this->assertEquals(55, (new _)->get(['number' => 55], '["number"]'));
	}
	function testGetArrayDefault() {
		$this->assertEquals('not found', (new _)->get(['missed' => 22], '["something"]', 'not found'));
	}
	function testGetComplexAccess() {
		$obj = new Complex;
		$this->assertEquals('found', (new _)->get($obj, '::access()["something"]->something'));
	}
	function testGetExploit() {
		//Demostrating possible exploit if second parameter is user input
		$obj = new Complex;
		$userInput = '->exploit(call_user_func(function(){ echo "userDefinedCodeHere"; }))';
		(new _)->get($obj, $userInput);
		$this->expectOutputString('userDefinedCodeHere');
	}
}