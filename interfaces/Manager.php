<?php
/**
 * Interface Event
 * @package interfaces
 * @subpackage  elise.events
 * @author Alex Orlov <mail@alexxorlovv.name>
 * @version 1.0.0
 * @since 2015-02-28
 * @license   MIT
 * @copyright  2015 extriml
 */

namespace elise\events\interfaces;

interface Manager
{
	/**
     * Priority Levels
     */
    const HIGH = 150;

    const NORMAL = 100;
    
    const LOW = 50;

    /**
     * Run Event
     * @param  string  $eventType
     * @param  object  $source   
     * @param  mixed  $data      
     * @param  boolean $canceled 
     * @return null
     */
     public function run($eventType, $source = null, $data = null, $canceled = true);


    /**
     * Setup event handler
     * @param  string $eventType
     * @param  mixed $handler
     * @param  int $priority
     * @return null
     */
     public function on($eventType, $handler, $priority = self::NORMAL);
}