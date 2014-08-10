<?php

namespace Puffer;

class Profile extends Core implements \ArrayAccess
{

    public function __construct($data)
    {

        if (is_array($data)) {
            return $this->setData($data);
        }

        $data = $this->get('profiles/' . $data);
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

    private function getUpdates($type, $options = [])
    {
        $result = $this->get('profiles/' . $this->id . '/updates/' . $type, $options);

        if (!isset($result['updates'])) {
            return [];
        }

        $return = [];
        foreach ($result['updates'] as $update) {
            $return[] = new Update($update);
        }

        return $return;
    }

    public function sent($options = [])
    {
        return $this->getUpdates('sent', $options);
    }

    public function pending($options = [])
    {
        return $this->getUpdates('pending', $options);
    }

    public function create($text, $options = [])
    {
        // TODO: Check and remove "profile_ids" in options
        $result = $this->post('updates/create', [
            'text' => $text,
            'profile_ids' => [$this->id]
        ] + $options);

        $result = (object) $result;

        if (isset($result->updates) and !empty($result->updates)) {

            foreach ($result->updates as &$update) {
                $update = new Update($update);
            }

        }

        return $result;
    }

    public function updateSchedules(array $schedules = [])
    {
        return $this->post('profiles/' . $this->id . '/schedules/update', [
            'schedules' => $schedules
        ]);
    }

}
