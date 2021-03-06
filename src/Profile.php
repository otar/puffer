<?php

namespace Puffer;

class Profile extends Core implements \ArrayAccess
{

    use Traits\Populate;

    const PATTERN_TWITTER_USERNAME = '/^@?(\w){1,15}$/';

    /**
     * @param  mixed  $input
     * @return object Returns Puffer\Profile object with profile data as public attributes.
     */
    public function __construct($input)
    {

        switch (true) {

            // Populate object with an input data
            case is_array($input):
                return $this->populate($input);

            // Check if input is a Twitter username (starts with @ character)
            case $this->matches(self::PATTERN_TWITTER_USERNAME, $input):
                return $this->findProfileByTwitterUsername($input);

            // Check if input is an ID of a profile (MongoDB ObjectID)
            case $this->matches(self::PATTERN_MONGODB_OBJECTID, $input):
                $data = $this->get('profiles/' . $input);

                return $this->populate($data);

        }

        throw new Exception('You have initiated a Profile object with a bad "input" argument.');

    }

    private function findProfileByTwitterUsername($username)
    {
        $match_username = ltrim(strtolower($username), '@');
        $profiles = new Profiles;
        foreach ($profiles as $profile) {
            if ($profile->service == 'twitter' and $match_username === strtolower($profile->service_username)) {
                return $this->populate((array) $profile);
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
        if (NULL === $key) {
            throw new Exception('Appending value on a Profile object is not allowed.');
        }
        $this->{$key} = $value;
    }

    public function offsetUnset($key)
    {
        if (!$this->offsetExists($key)) {
            throw new Exception('Index is out of range on the Profile object.');
        }
        unset($this->{$key});
    }

    private function getUpdates($type, $options = [])
    {
        $result = $this->get('profiles/' . $this->id . '/updates/' . $type, $options);

        if (!isset($result['updates'])) {
            return [];
        }

        // Wrap result updates in the Update class
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

    public function __get($name)
    {
        switch (true) {
            case 'sent':
                return $this->sent();
            case 'pending':
                return $this->pending();
        }
        throw new Exception('You have called an undefined attribute "' . $name . '".');
    }

    public function create($text, $options = [])
    {
        // Remove unnecessary options, we already pass them manually below.
        if (isset($options['text'])) {
            unset($options['text']);
        }
        if (isset($options['profile_ids'])) {
            unset($options['profile_ids']);
        }

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
