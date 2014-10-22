<?php

namespace Puffer;

use Puffer\Storages\Session;

class Puffer extends Core
{

    public function __construct(array $options)
    {

        if (empty($options)) {
            throw new Exception('Options argument is empty, please pass an array with auth parameters.');
        }

        self::$options = array_merge(self::$options, $options);

        if (
                isset(self::$options['storage'])
            and is_object(self::$options['storage'])
            and self::$options['storage'] instanceof Storages\StorageInterface
        ) {
            self::$storage = self::$options['storage'];
        } else {
            self::$storage = new Storages\Session;
        }

        isset(self::$options['access_token']) and self::setAccessToken(self::$options['access_token']);

        // TODO: Separate "listen" to code method
        if (!filter_has_var(INPUT_GET, 'code')) {
            return;
        }

        $code = filter_input(INPUT_GET, 'code');
        if (empty($code)) {
            throw new Exception('Incoming parameter "code" either does not exist or is empty.');
        }

        self::getAccessToken($code);
    }

    public function isAuthorized()
    {
        return self::$storage->has('access_token');
    }

    public function getAuthUrl()
    {
        return self::$api_urls['authorize'] . '?' . http_build_query([
            'client_id' => self::$options['consumer_key'],
            'redirect_uri' => self::$options['callback_url'],
            'response_type' => 'code'
        ]);
    }

    public function __get($name)
    {

        switch ($name) {

            // User profile data
            case 'user':
                return (object) $this->get('user');

            // General configuration options
            case 'configuration':
                return (object) $this->get('info/configuration');

            // Get all profiles
            case 'profiles':
                return new Profiles;

        }

        throw new Exception('You have called an undefined attribute "' . $name . '".');

    }

    public function shares($link)
    {
        return $this->get('links/shares', [
            'url' => $link
        ]);
    }

}
