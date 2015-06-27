<?php
/**
 * Manager Test
 * @package tests
 * @subpackage  events
 * @author Alex Orlov <mail@alexxorlovv.name>
 * @version 1.0.0
 * @since 2015-02-28
 * @license   MIT
 * @copyright  2015 extriml
 */

namespace elise\events\tests;

use elise\events\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Manager
	 * @var EventManager
	 */
	public $manager;

	/**
	 * Event type
	 * @var string
	 */
	public $eventType;

	/**
	 * Test init
	 * @return void
	 */
	public function setUp()
	{
		$this->manager = new Manager;
		$this->eventType = "persons";
	}


	/**
	 * testInitGlobalyListener
	 * @return void
	 */
	public function testInitGlobalyListener()
	{
		$this->assertEquals(false, $this->manager->hasListeners($this->eventType));
		$this->manager->on($this->eventType, new Person);
		$this->assertEquals(true, $this->manager->hasListeners($this->eventType));
		$this->assertEquals(true, $this->manager->getListeners($this->eventType)[0]->isGlobal());
	}

	/**
	 * testInitLocalListener
	 * @return void
	 */
	public function testInitLocalListener()
	{
		$this->assertEquals(false, $this->manager->hasListeners($this->eventType));
		$this->manager->on($this->eventType.":brithday",function($event){
			return;
		});

		$this->assertEquals(true, $this->manager->hasListeners($this->eventType.":brithday"));
		$this->assertEquals(false, $this->manager->getListeners($this->eventType.":brithday")[0]->isGlobal());
	}


	/**
	 * Testing running event
	 * @return void
	 */
	public function testRunEvent()
	{
		 $this->manager->on($this->eventType, new Person);
		 $this->expectOutputString('PersonBrithday');
		 $this->manager->run($this->eventType.":brithday");
		 
	}

	/**
	 * Testing events response values
	 * @return void
	 */
	public function testEventResponseValues()
	{
		$this->manager->response(true);
		$this->manager->on($this->eventType, new Person);
		$this->manager->on($this->eventType, function($event){
			return "2";
		});

		$this->manager->run($this->eventType.":brithday");
		$response = $this->manager->getResponses();
		$this->assertEquals("1", $response[0]);
		$this->assertEquals("2", $response[1]);
	}

	/**
	 * Testing priority
	 * @return void
	 */
	public function testListenerPriorityRunner()
	{
		$this->manager->response(true);
		$this->manager->priority(true);

		$this->manager->on($this->eventType, new Person, Manager::LOW);
		$this->manager->on($this->eventType.":brithday", function($event){
			return "2";
		}, Manager::HIGH);
		$this->manager->on($this->eventType.":brithday",function($event){
			return "3";
		}, Manager::NORMAL);

		$this->manager->run($this->eventType.":brithday");
		$response = $this->manager->getResponses();
		$this->assertEquals("2", $response[0]);
		$this->assertEquals("3", $response[1]);
		$this->assertEquals("1", $response[2]);

	}

	/**
	 * Testing dettach listener
	 * @return void
	 */
	public function testDettachListener()
	{
		$this->assertEquals(false, $this->manager->hasListeners($this->eventType.":brithday"));
		$this->manager->on($this->eventType.":brithday", function($event){ return "1";});
		$this->assertEquals(true, $this->manager->hasListeners($this->eventType.":brithday"));

		$this->manager->detach($this->eventType.":brithday");
		$this->assertEquals(false, $this->manager->hasListeners($this->eventType.":brithday"));
	}


	/**
	 * Testing allowed listener off on
	 * @return void
	 */
	public function testAllowedListener()
	{
		$this->manager->response(true);
		$this->manager->on($this->eventType.":brithday", function($event){
			return "1";
		});

		$this->manager->allowedListeners($this->eventType.":brithday", false);
		$this->manager->run($this->eventType.":brithday");
		$response = $this->manager->getResponses();
		$this->assertEquals(0, sizeof($response));

		$this->manager->allowedListeners($this->eventType.":brithday", true);
		$this->manager->run($this->eventType.":brithday");
		$response = $this->manager->getResponses();
		$this->assertEquals(1, sizeof($response));


	}

	/**
	 * Testing stopping next events
	 * @return void
	 */
	public function testStopEvent()
	{
		$this->manager->response(true);
		$this->manager->on($this->eventType.":brithday", function($event){
			if ($event->isCancelable() === true) {
				$event->stop();
			}
			return "2";
		});	
		$this->manager->on($this->eventType, new Person);
		$this->manager->on($this->eventType.":brithday", function($event){
			return "3";
		});

		$this->manager->run($this->eventType.":brithday",null,array(),false);
		$this->assertEquals(3,sizeof($this->manager->getResponses()));

		$this->manager->run($this->eventType.":brithday",null,array(),true);
		$this->assertEquals(1,sizeof($this->manager->getResponses()));
	}




	
}	



/**
 * Class Handler to test
 */
class Person
{
	/**
	 * Event name listener
	 * @param  Event $event
	 * @param  Object $component
	 * @param  array $data
	 * @return string
	 */
	function brithday($event,$component,$data)
	{
		echo "PersonBrithday";
		return "1";
	}
}
