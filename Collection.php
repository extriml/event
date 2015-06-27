<?php
/**
 * Event Collection
 * @package events
 * @subpackage  elise
 * @author Alex Orlov <mail@alexxorlovv.name>
 * @version 1.0.0
 * @since 2015-02-28
 * @license   MIT
 * @copyright  2015 extriml
 */

namespace elise\events;

class Collection implements \Iterator, \Countable
{

    /**
     * Elements collection
     * @var array
     */
    public $elements = array();


    /**
     * Priorities elements
     * @var array
     */
    public $priority = array();


    /**
     * Priorities using?
     * @var bool
     */
    protected $isPriority = false;


    /**
     * Remove element
     * @param  int $key
     * @return void
     */
    public function remove($key)
    {
        unset($this->elements[$key]);
        unset($this->priority[$key]);

        $this->elements = array_values($this->elements);
        $this->priority = array_values($this->priority);

        $this->sort();
    }

    /**
     * Setup using priorities or getting setup
     * @param  mixed $priority
     * @return mixed
     */
    public function priority($priority = null)
    {
        if (is_bool($priority) === true) {
            $this->isPriority = $priority;   
        }
        if (is_null($priority) === true) {
            return $this->isPriority;
        }
        return null;
    }


    /**
     * Add element
     * @param mixed $elem
     * @param int $priority
     */
    public function add($elem, $priority)
    {
        $priority = intval($priority);
        array_push($this->elements, $elem);
        array_push($this->priority, $priority);
    }


    /**
     * Current Iterator
     * @return array
     */
    public function current()
    {
        return $this->elements[$this->key()];
    }


    /**
     * Sort elements
     * @return void
     */
    protected function sort()
    {
        if ($this->priority() === true) {
            arsort($this->priority);  
        }else {
            ksort($this->priority);
        }  
    }

    /**
     * Reset Iterator
     * @return void
     */
    public function rewind() //Сброс
    {
        $this->sort();
        reset($this->priority);
    }


    /**
     * Valid Iterator
     * @return bool
     */
    public function valid()
    {
        return isset($this->priority[$this->key()]);
    }


    /**
     * Now key element Iterator
     * @return int
     */
    public function key()
    {
        return key($this->priority);
    }


    /**
     * Next Iterator
     * @return array
     */
    public function next() //Следующий элемент
    {

        next($this->priority);

        return $this->elements[$this->key()];
    }


    /**
     * Size collection Countable
     * @return int
     */
    public function count()
    {
        return sizeof($this->elements);
    }

    /**
     * Export array
     * @return array
     */
    public function export()
    {
        return $this->elements;
    }
}
