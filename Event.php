<?php
/**
     * Event
     * @package events
     * @subpackage  elise
     * @author Alex Orlov <mail@alexxorlovv.name>
     * @version 1.0.0
     * @since 2015-02-28
     * @license   MIT
     * @copyright  2015 extriml
     */
namespace elise\events;

use elise\events\exceptions\InvalidArgumentException;

class Event
{
    /**
     * Property cancel event
     * @var boolean
     */
    protected $cancelable = true;

    /**
     * Event is stopped?
     * @var boolean
     */
    protected $stopped = false;

    /**
     * Event name
     * @var string
     */
    protected $type = "";

    /**
     * Called object
     * @var object
     */
    protected $source;

    /**
     * Data called event
     * @var array
     */
    protected $data = array();



    /**
     * Init event
     * @param string  $type   
     * @param object  $source 
     * @param array | null  $data    
     * @param bool $cancelable
     */
    public function __construct($type, $source, $data = null, $cancelable = true)
    {
        $this->setType($type);
        $this->setSource($source);
        $this->setData($data);
        $this->setCancelable($cancelable);
    }
    

    /**
     * Data setting
     * @param array | null $data
     */
    public function setData($data)
    {
        if (is_null($data) === false && is_array($data) === true) {
            $this->data = $data; 
            return; 
        } 

        throw new InvalidArgumentException("Error Event - invalid type(data) in setData");    
    }

    /**
     * Data getting
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Source setting
     * @param object | null $source
     * @return void
     */
    public function setSource($source)
    {
        if (is_object($source) === false && is_null($source) === false) {
            throw new InvalidArgumentException("Invalid parameter soruce");
            
        }
            $this->source = $source;
    }

    /**
     * Source getting
     * @return object | null
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Type setting
     * @param string $type
     * @return void
     */
    public function setType($type)
    {
        if (is_string($type) === false) {
            throw new InvalidArgumentException("Invalid parameter type");       
        }
        $this->type = $type;
    }

    /**
     * Type getting
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Is Cancelable?
     * @return bool
     */
    public function isCancelable()
    {
        return (bool) $this->cancelable;
    }

    /**
     * Cancelable getting
     * @return bool
     */
    public function getCancelabe()
    {
        return $this->cancelable;
    }

    /**
     * Cancelable setting
     * @param bool $cancel
     * @return void
     */
    public function setCancelable($cancel)
    {
        if (is_bool($cancel) === false) {
            throw new InvalidArgumentException("Invalid parameter cancelable");
        }
        $this->cancelable = $cancel;
    }

    /**
     * Stopping event
     * @return void
     */
    public function stop()
    {
        if ($this->cancelable === true) {
            $this->stopped = true;   
        }else {
            throw new InvalidArgumentException("Is non cancelable event");   
        }
      
    }

    /**
     * Is stopped event?
     * @return bool
     */
    public function isStopped()
    {
        return $this->stopped;
    }
    
}
