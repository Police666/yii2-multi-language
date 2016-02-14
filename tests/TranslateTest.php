<?php

/**
 * Created by Navatech.
 * @project yii-basic
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    14/02/2016
 * @time    7:26 CH
 */
class TranslateTest extends PHPUnit_Framework_TestCase {

	private $translate;

	public function setUp() {
		$this->translate = new \navatech\language\Translate();
	}

	public function tearDown() {
		$this->translate = null;
	}

	public function testInstanceOf() {
		$this->assertInstanceOf('navatech\language', $this->translate);
	}
}
