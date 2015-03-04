<?php
/**
 * Collection Test
 * @package tests
 * @subpackage  events
 * @author Alex Orlov <mail@alexxorlovv.name>
 * @version 1.0.0
 * @since 2015-02-28
 * @license   MIT
 * @copyright  2015 extriml
 */

namespace elise\events\tests;

use elise\events\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Object Collection
     * @var Collection
     */
    public $collection;

    /**
     * Init Test
     * @return void
     */
    function setUp()
    {
        $this->collection = new Collection; 
    }

    /**
     * testPriority
     * @return void
     * @dataProvider priorityProvider
     */
    function testSetupPriority($priority)
    {
        $this->assertEquals(null,$this->collection->priority($priority));
        $this->assertAttributeSame($priority,"isPriority",$this->collection);
    }

    /**
     * testGetPriority
     * @return void
     */
    function testGetPriority()
    {
        $this->collection->priority(true);
        $this->assertEquals(true,$this->collection->priority());
        
    }

    /**
     * testAdd
     * @param  mixed $element
     * @param  int $priority
     * @dataProvider provider
     */
    function testAdd($element,$priority)
    {
        $oldCount = $this->collection->count();
        $this->collection->add($elment,$priority);
        $this->assertNotEquals($oldCount,$this->collection->count());
    }


    /**
     * testGettingNotPriority
     * @return void 
     */
    function testGettingNotPriority()
    {
        $this->collection->priority(false);
        $this->collection->add(1,150);
        $this->collection->add(2,150);
        $this->collection->add(3,50);
        $this->collection->add(4,75);

        $i=1;
        foreach ($this->collection as $value) {
            $this->assertEquals($i,$value);
            $i++;
        }
    }

    /**
     * testGettingPriority
     * @return void
     */
    function testGettingPriority()
    {
        $this->collection->priority(true);
        $this->collection->add(1,40);
        $this->collection->add(2,100);
        $this->collection->add(3,150);
        $this->collection->add(4,150);
        
        $i = 4;

        foreach ($this->collection as $value) {
            $this->assertEquals($i,$value);
            $i--;
        }
    }

    function testRemove()
    {
    	$this->assertEquals($this->collection->count(),0);
    	$this->collection->add(1,40);
    	$this->assertEquals($this->collection->count(),1);
    	$this->collection->remove(0);
    	$this->assertEquals($this->collection->count(),0);
    }


    /**
     * priorityProvider
     * @return array
     */
    function priorityProvider()
    {
        return array(
            array(false),
            array(true)
            );
    }

    /**
     * provider add elements
     * @return array
     */
    function provider()
    {
        return array(
            array("1",100),
            array("2",50),
            array("3",150)
            );
    }

}
