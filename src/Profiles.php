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

    public function offsetSet($index, $profile)
    {
        if (!is_object($profile) or !($profile instanceof Profile)) {
            throw new Exception('You are setting or appending a either a non-object or an object which is not instance of Profile.');
        }
        if (NULL === $index) {
            $this->profiles[] = $profile;
        } else {
            $this->profiles[$index] = $profile;
        }
    }

    public function offsetUnset($index)
    {
        if (!$this->offsetExists($index)) {
            throw new Exception('Profile index is out of range.');
        }
        unset($this->profiles[$index]);
    }

    public function count()
    {
        return count($this->profiles);
    }

}
