<?php

namespace Puffer;

class Profile extends Core implements \ArrayAccess
{

    /**
     * @param  mixed  $input
     * @return object Returns Puffer\Profile object with profile data as public attributes.
     */
    public function __construct($input)
    {

        switch (true) {
            case is_array($input):
                return $this->setData($input);
            case preg_match('/^@?(\w){1,15}$/', $input):
                return $this->findProfileByTwitterUsername($input);
            default:
                // TODO: check mongodb id on regex
                $data = $this->get('profiles/' . $input);
                $this->setData($data);

                if (!isset($this->id)) {
                    throw new Exception('Profile data is corrupted, it doesn\'t have an "id" parameter.');
                }
                break;
        }

    }

    private function setData(array $data = [])
    {

        if (empty($data)) {
            throw new Exception('Can not populate a profile with an empty array. Data is empty.');
        }

        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

    }

    private function findProfileByTwitterUsername($username)
    {
        $match_username = ltrim(strtolower($username), '@');
        $profiles = new Profiles;
        foreach ($profiles as $profile) {
            if ($profile->service == 'twitter' and $match_username === strtolower($profile->service_username)) {
                return $this->setData((array) $profile);
            }
        }
        throw new Exception('Can not find profile for the Twitter username "' . $username . '".');
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
        // Remove unnecessary options, we already pass them manually below.
        isset($options['text']) and unset($options['text']);
        isset($options['profile_ids']) and unset($options['profile_ids']);

        $result = $this->post('updates/create', $options + [
            'text' => $text,
            'profile_ids' => [$this->id]
        ]);

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
