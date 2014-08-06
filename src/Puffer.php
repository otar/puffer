<?php

namespace Puffer;

use Puffer\Storages\Session;

class Puffer extends Core
{

    private $map_endpoints = [
        'user' => 'user',
        'configuration' => 'info/configuration'
    ];

    public function __construct(array $options)
    {
        if (!is_array($options) or empty($options)) {
            // TODO: throw exception
        }

        self::$options = array_merge(self::$options, $options);

        if (isset(self::$options['storage']) and is_object(self::$options['storage']) and self::$options['storage'] instanceof Storages\StorageInterface) {
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
            // TODO: throw exception
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
        if (!array_key_exists($name, $this->map_endpoints)) {
            // TODO: throw exception
        }

        return (object) $this->get($this->map_endpoints[$name]);
    }

    public function shares($link)
    {
        return $this->get('links/shares', [
            'url' => $link
        ]);
    }

}
