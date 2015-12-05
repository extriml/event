<?php
/**
 * Event Test
 * @package tests
 * @subpackage  events
 * @author Alex Orlov <mail@alexxorlovv.name>
 * @version 1.0.0
 * @since 2015-02-28
 * @license   MIT
 * @copyright  2015 extriml
 */

namespace elise\events\tests;

use elise\events\Event;

class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Property cancel event
     * @var boolean
     */
    public $cancelable;

    /**
     * Event is stopped?
     * @var boolean
     */
    public $stopped;

    /**
     * Event name
     * @var string
     */
    public $type;

    /**
     * Called object
     * @var object
     */
    public $source;

    /**
     * Data called event
     * @var array
     */
    public $data;

    public $event;

	public function setUp()
	{
		$this->cancelable = true;
		$this->stopped = false;
		$this->type = "TestEvent";
		$this->data = array("test" => 1,"test2"=> 1);
		$this->source = new \stdClass();
		$this->event = new Event($this->type,$this->source,$this->data,$this->cancelable);
	}

	public function testSetData()
	{
		$data = array("testing");
		$this->event->setData($data);
		$this->assertAttributeSame($data,"data",$this->event);
	}

	public function testGetData()
	{
		$this->assertEquals($this->event->getData(),$this->data);
	}

	public function testSetSource()
	{
		$source = new \StdClass();
		$source->test = 1;
		$this->event->setSource($source);
		$this->assertAttributeSame($source,"source",$this->event);
	}

	public function testGetSource()
	{
		$this->assertEquals($this->event->getSource(),$this->source);
	}

	public function testSetType()
	{
		$type = "eventType";
		$this->event->setType($type);
		$this->assertAttributeSame($type,"type",$this->event);
	}

	public function testGetType()
	{
		$this->assertEquals($this->event->getType(),$this->type);
	}

	public function testSetCancelabe()
	{
		$cancelable = false;
		$this->event->setCancelable($cancelable);
		$this->assertAttributeSame($cancelable,"cancelable",$this->event);
	}

	public function testGetCancelable()
	{
		$this->assertEquals($this->event->getCancelabe(),$this->cancelable);
	}

	public function testIsCancelable()
	{
		$this->assertTrue($this->event->isCancelable());
	}

	public function testStop()
	{
		$this->assertAttributeSame(false,"stopped",$this->event);
		$this->event->stop();
		$this->assertAttributeSame(true,"stopped",$this->event);
	}

	public function testIsStoped()
	{
		$this->assertFalse($this->event->isStopped());
		$this->event->stop();
		$this->assertTrue($this->event->isStopped());
	}
}
