<?php

namespace Puffer;

class Update extends Core implements \ArrayAccess
{

    public function __construct($data)
    {
        if (is_array($data)) {
            return $this->setData($data);
        }

        $data = $this->get('updates/' . $data);
        $this->setData($data);

        if (!isset($this->id)) {
            // TODO: throw exception
        }
    }

    private function setData(array $data)
    {
        if (empty($data)) {
            // TODO: throw exception
        }

        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function offsetExists($key)
    {
        return isset($this->{$key});
    }

    public function offsetGet($key)
    {
        return $this->offsetExists($key) ? $this->{$key} : null;
    }

    public function offsetSet($key, $value)
    {
        // TODO: check instance type, it should implement Profile object
        if (NULL === $key) {
            // TODO: Throw an exception?
        } else {
            $this->{$key} = $value;
        }
    }

    public function offsetUnset($key)
    {
        if (!$this->offsetExists($key)) {
            // TODO: throw exception
        }
        unset($this->{$key});
    }

    public function interactions($event, array $options = [])
    {
        $options['event'] = $event;

        return $this->get('updates/' . $this->id . '/interactions', $options);
    }

    public function edit($text, array $options = [])
    {
        return $this->post('updates/' . $this->id . '/update', [
            'text' => $text
        ] + $options);
    }

    public function share()
    {
        return $this->post('updates/' . $this->id . '/share');
    }

    public function destroy()
    {
        return (object) $this->post('updates/' . $this->id . '/destroy');
    }

    public function moveToTop()
    {
        return $this->post('updates/' . $this->id . '/move_to_top');
    }

}
