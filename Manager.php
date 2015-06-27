<?php
/**
 * Event Manager
 * @package events
 * @subpackage  elise
 * @author Alex Orlov <mail@alexxorlovv.name>
 * @version 1.0.0
 * @since 2015-02-28
 * @license   MIT
 * @copyright  2015 extriml
 */

namespace elise\events;


use elise\events\Event;
use elise\events\Collection;
use elise\events\Listener;
use elise\events\exceptions\InvalidArgumentException;
use elise\events\exceptions\ListenerNotFoundException;
use elise\events\interfaces\ManagerInterface as ManagerAwareInterface;

class Manager implements ManagerAwareInterface
{
    /**
     * Events list
     * @var array
     */
    protected $events = array();

    /**
     * Priority using?
     * @var boolean
     */
    protected $priority = false;

    /**
     * Responses list
     * @var array
     */
    protected $response = array();

    /**
     * Response saving?
     * @var boolean
     */
    protected $responseable = false;

    /**
     * Init manager
     * @param boolean $priority     
     * @param boolean $responseable
     */
    public function __construct($priority = false, $responseable = false)
    {
        $this->priority = $priority;
        $this->responseable = $responseable;
    }


    /**
     * Set type saving responses or return type
     * @param  mixed $responses
     * @return mixed
     */
    public function response($responses = null)
    {
        if (is_bool($responses) === true) {
            $this->responseable = $responses;
        }else {
            return $this->responseable;
        }
    }


    /**
     * Getting responses
     * @return array
     */
    public function getResponses()
    {
        return $this->response;
    }


    /**
     * Set type using priorities or return type
     * @param  mixed $priority
     * @return mixed
     */
    public function priority($priority = null)
    {
        if (is_bool($priority) === true) {
            $this->priority = $priority;
        }else {
            return $this->priority;
        }
    }


    /**
     * Run Event
     * @param  string  $eventType
     * @param  object  $source   
     * @param  mixed  $data      
     * @param  boolean $canceled 
     * @return null
     */
    public function run($eventType, $source = null, $data = array(), $canceled = true)
    {
      
        if (strpos($eventType, ":") === false) {
            throw new InvalidArgumentException("Invalid Event Type");
        }
        if (is_object($source) === false && is_null($source) === false) {
            throw new InvalidArgumentException("Invalid Handler");
        }
        if (is_bool($canceled) === false) {
            throw new InvalidArgumentException("Invalid Canceled");
        }

        list($collectionName, $eventName) = $this->exportFormat($eventType);

        if (isset($this->events[$collectionName]) === false) {
            return;
        }

        /* Create event */
        $event = new Event($eventName, $source, $data, $canceled);

        /* Getting collection and setup priority */
        $collection = $this->events[$collectionName];
        $collection->priority($this->priority());


        $responses = array();

        foreach ($collection as $value) {
            if ($eventName !== $value->getName() && $value->isGlobal() === false) {
                continue;
            }
            if ($value->allowed() === false) {
                continue;
            }

            $handler = $value->getHandler();
            $parameters = array($event, $source, $data);

            if (is_callable($handler) === true) {
                array_push($responses, call_user_func_array($handler, $parameters));
            }

            elseif (is_object($handler) === true) {
                if (method_exists($handler, $eventName)) {
                    array_push($responses, call_user_func_array(array($handler, $eventName), $parameters));
                }     
            }
           
            if ($event->isStopped() === true) {
                break;
            }
            continue;   
        }
            if ($this->response() === true) {
                $this->response = $responses;
            }
    }




    /**
     * Setup event handler
     * @param  string $eventType
     * @param  mixed $handler
     * @param  int $priority
     * @return null
     */
    public function on($eventType, $handler, $priority = self::NORMAL)
    {
        if (is_object($handler) === false && 
            is_callable($handler) === false) {
            throw new InvalidArgumentException("Invalid Handler");
        }

        if (is_int($priority) === false) {
            throw new InvalidArgumentException("Invalid priority");
        }

        list($collectionName, $eventName) = $this->exportFormat($eventType);
        
        if (isset($this->events[$collectionName]) === false) {
            $this->events[$collectionName] = new Collection();
        }
        $this->events[$collectionName]->add(new Listener($handler, $eventName), $priority);

    }


    /**
     * Export data string
     * @param  string $eventType
     * @return array
     */
    public function exportFormat($eventType)
    {
        if (is_string($eventType) === false) {
            throw new InvalidArgumentException("Error -allowedListeners eventType  type invalid. ");
        }
        return explode(":", strtolower(trim($eventType)));
    }



    /**
     * Getting  listeners
     * @param  string $eventType
     * @return array           
     */
    public function getListeners($eventType)
    {


        list($collectionName, $eventName) = $this->exportFormat($eventType);
        
        if (is_null($eventName) === true) {
            if (isset($this->events[$collectionName])) {
                return $this->events[$collectionName]->export();    
            }
            return array();
        }

        $listeners = array();
        if (isset($this->events[$collectionName]) === true) {
            foreach ($this->events[$collectionName] as $value) {
                if ($eventName == $value->getName()) {
                    array_push($listeners, $value);
                }
            
            }
        }
        return $listeners;
    }


    /**
     * Found is listener?
     * @param  string  $eventType
     * @return boolean            
     */
    public function hasListeners($eventType)
    {

        list($collectionName, $eventName) = $this->exportFormat($eventType);
        
        if (is_null($eventName) === true) {  
            if (isset($this->events[$collectionName]) === false) {
                return false;  
            } 
            return true;
        } 
        if (isset($this->events[$collectionName]) === true) {      
            foreach ($this->events[$collectionName] as $value) {
                if ($eventName == $value->getName()) {
                    return true;                
                }
            }
        }
        return false;

    }

    /**
     * Dettach listener
     * @param  string $eventType
     * @return void            
     */
    public function dettach($eventType)
    {

       list($collectionName, $eventName) = $this->exportFormat($eventType);

        if (is_null($eventName) === true) {  
            if (isset($this->events[$collectionName]) === false) {
                throw new ListenerNotFoundException("Error Listeners not found.");
            }
            unset($this->events[$collectionName]);
            return;
        }

        foreach ($this->events[$collectionName] as $key => $value) {
            
            if ($eventName == $value->getName()) {
                $this->events[$collectionName]->remove($key);                    
            }
        }
    }

    /**
     * Synonym dettach
     * @param  string $eventType
     * @return void
     */
    public function detach($eventType)
    {
        $this->dettach($eventType);
    }

    public function allowedListeners($eventType, $allowed)
    {
        if (is_bool($allowed) === false) {
            throw new InvalidArgumentException("Error - allowedListeners allowed type invalid.");
        }

        list($collectionName, $eventName) = $this->exportFormat($eventType);

        if (is_null($eventName) === true) {  
            if (isset($this->events[$collectionName]) === false) {
                throw new ListenerNotFoundException("Error Listeners not found.");
            }
           
            foreach ($this->events[$collectionName] as $value) {
                $value->allowed($allowed);
            }
            return;
        }

        foreach ($this->events[$collectionName] as $key => $value) {
            
            if ($eventName == $value->getName()) {
                $value->allowed($allowed);                   
            }
        }
    }

}
