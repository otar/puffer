<?php

namespace Puffer;

class Update extends Core implements \ArrayAccess
{

    public function __construct($data)
    {
        if (is_array($data)) {
            return $this->populate($data);
        }

        $data = $this->get('updates/' . $data);
        $this->populate($data);

        if (!isset($this->id)) {
            throw new Exception('Update data is corrupted, it doesn\'t have an "id" parameter.');
        }
    }

    private function populate(array $data)
    {
        if (empty($data)) {
            throw new Exception('Can not populate an update with an empty array. Data is empty.');
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
        if (NULL === $key) {
            throw new Exception('Appending value on the Update object is not allowed.');
        }
        $this->{$key} = $value;
    }

    public function offsetUnset($key)
    {
        if (!$this->offsetExists($key)) {
            throw new Exception('Index is out of range on the Update object.');
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
