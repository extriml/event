<?php
/**
 * Event Listener
 * @package events
 * @subpackage  elise
 * @author Alex Orlov <mail@alexxorlovv.name>
 * @version 1.0.0
 * @since 2015-02-28
 * @license   MIT
 * @copyright  2015 extriml
 */

namespace elise\events;

use elise\events\Manager;
use elise\events\Collection;
use elise\events\exceptions\InvalidArgumentException;


class Listener
{
    /**
     * Handler action
     * @var mixed
     */
    protected $handler;

    /**
     * Name listener
     * @var string
     */
    protected $name = null;

    /**
     * Is globaly?
     * @var bool
     */
    protected $globaly = true;

    
    /**
     * Is allowed listener?
     * @var boolean
     */
    protected $allowed = true;





    /**
     * Init listener
     * @param mixed $handler
     * @param string $eventName
     */
    function __construct($handler, $eventName = null)
    {
        if (is_object($handler) === false AND 
            is_callable($handler) === false) {
            throw new InvalidArgumentException("Not a valid handler type"); 
        }
        if (is_null($eventName) === false) {
            if (is_string($eventName) === false) {
                 throw new InvalidArgumentException("Not a valid eventName type"); 
            }
            $this->globaly = false;
        }

        $this->handler = $handler;
        $this->name = $eventName;
    }


    /**
     * Setup allowed
     * @param  null | bool $allowed
     * @return null | bool
     */
    function allowed($allowed = null)
    {
        if (is_null($allowed) === false and is_bool($allowed) === false) {
            throw new InvalidArgumentException("Listener allowed error type");
        }

        if (is_null($allowed) === true) {
            return $this->allowed;
        }
        $this->allowed = $allowed;
    }


    /**
     * Get type listener
     * @return bool
     */
    function isGlobal()
    {
        return $this->globaly;
    }

    /**
     * Get name listener
     * @return string
     */
    function getName()
    {
        return $this->name;
    }

    /**
     * Get handler listener
     * @return mixed
     */
    function getHandler()
    {
        return $this->handler;
    }
}