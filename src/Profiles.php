<?php

namespace Puffer;

class Profiles extends Core implements \Iterator, \ArrayAccess, \Countable
{

    private $position = 0,
        $profiles = [];

    public function __construct()
    {

        $profiles = $this->get('profiles');

        foreach ($profiles as $profile_id) {
            $this->profiles[] = new Profile($profile_id);
        }

    }

    public function all()
    {
        return $this->profiles;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return isset($this->profiles[$this->position]);
    }

    public function current()
    {
        return $this->profiles[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function offsetExists($index)
    {
        return isset($this->profiles[$index]);
    }

    public function offsetGet($index)
    {
        return $this->offsetExists($index) ? $this->profiles[$index] : null;
    }

    public function offsetSet($index, $value)
    {
        // TODO: check instance type, it should implement Profile object
        if (NULL === $index) {
            $this->profiles[] = $value;
        } else {
            $this->profiles[$index] = $value;
        }
    }

    public function offsetUnset($index)
    {
        if (!$this->offsetExists($index)) {
            // TODO: throw exception
        }
        unset($this->profiles[$index]);
    }

    public function count()
    {
        return count($this->profiles);
    }

}
