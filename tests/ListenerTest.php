<?php
/**
 * Listener Test
 * @package tests
 * @subpackage  events
 * @author Alex Orlov <mail@alexxorlovv.name>
 * @version 1.0.0
 * @since 2015-02-28
 * @license   MIT
 * @copyright  2015 extriml
 */

namespace elise\events\tests;

use elise\events\Listener;

class ListenerTest extends \PHPUnit_Framework_TestCase
{
	public $name;
	public $handler;
	public $allowed;
	public $globaly;
	public $listener;


	public function setUp()
	{
		$this->name = "TestName";
		$this->handler = function(){};
		$this->allowed = true;
		$this->globaly = true;
		$this->listener = new Listener($this->handler,$this->name);
	}

	public function testGetAllowed()
	{
		$this->assertEquals($this->listener->allowed(),$this->allowed);
	}

	public function testSetAllowed()
	{
		$allowed = false;
		$this->listener->allowed($allowed);
		$this->assertEquals($this->listener->allowed(),$allowed);
	}

	public function testIsGlobal()
	{
		$this->assertEquals(false,$this->listener->isGlobal());
	}

	public function testGetName()
	{
		$this->assertEquals($this->listener->getName(),$this->name);
	}

	public function testGetHandler()
	{
		$this->assertEquals($this->listener->getHandler(),$this->handler);
	}

	public function testInitNotGlobal()
	{
		$listener = new Listener($this->handler,$this->name);
		$this->assertEquals($listener->isGlobal(),false);
	}

	public function testInitGlobal()
	{
		$listener = new Listener($this->handler);
		$this->assertEquals($listener->isGlobal(),true);
	}
}
