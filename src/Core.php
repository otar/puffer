<?php

namespace Puffer;

use GuzzleHttp\Client;

class Core
{

    const API_VERSION = 1,
        PATTERN_MONGODB_OBJECTID = '/^[0-9a-fA-F]{24}$/';

    protected static $options = [
            'consumer_key' => NULL,
            'consumer_secret' => NULL,
            'callback_url' => NULL
        ],
        $api_urls = [
            'base' => 'https://api.bufferapp.com/{version}/',
            'authorize' => 'https://bufferapp.com/oauth2/authorize',
            'access_token' => 'https://api.bufferapp.com/1/oauth2/token.json'
        ],
        $client,
        $storage;

    private static $error_codes = [];

    protected static function getAccessToken($code)
    {
        $request = (new Client)->post(self::$api_urls['access_token'], null, [
            'client_id' => self::$options['consumer_key'],
            'client_secret' => self::$options['consumer_secret'],
            'redirect_uri' => self::$options['callback_url'],
            'code' => $code,
            'grant_type' => 'authorization_code'
        ]);

        $response = $request->send()->json();

        self::setAccessToken($response['access_token']);

    }

    public function get($endpoint, array $parameters = [])
    {
        return $this->call('get', $endpoint, [
            'query' => $parameters
        ]);
    }

    public function post($endpoint, array $data = [])
    {
        return $this->call('post', $endpoint, [
            'body' => $data
        ]);
    }

    protected function call($method, $endpoint, $options = [])
    {
        if (NULL === self::$client) {
            self::$client = new Client([
                'base_url' => [self::$api_urls['base'], [
                        'version' => self::API_VERSION
                    ]],
                'defaults' => [
                    'headers' => [
                        'Authorization' => 'Bearer ' . self::$storage->get('access_token')
                    ],
                    'exceptions' => FALSE
                ]
            ]);
        }

        $response = self::$client->$method($endpoint . '.json', $options);
        $json = $response->json();

        // Houston, we have a problem!
        if ($response->getStatusCode() >= 400) {

            if (isset($json['success'])) {

                $error_message = $this->getErrorMessage($json['code']);

                if ($error_message === NULL) {
                    $error_message = $json['message'];
                } else {
                    $error_message .= ' ' . $json['message'];
                }

                throw new Exception($error_message, $json['code'], $json['message']);

            }

            throw new \RuntimeException('Unexpected error occurred.');

        }

        return $json;
    }

    protected static function setAccessToken($token)
    {
        // TODO: Check access token on a regex pattern
        return self::$storage->set('access_token', $token);
    }

    protected function matches($pattern, $subject)
    {
        $match = preg_match($pattern, $subject);

        if (FALSE === $match) {
            return FALSE;
        }

        return $match === 1;
    }

    private function getErrorMessage($error_code)
    {
        if (empty(self::$error_codes)) {
            self::$error_codes = require_once 'ErrorCodes.php';
        }

        if (isset(self::$error_codes[$error_code])) {
            return self::$error_codes[$error_code] . '.';
        }

        return NULL;
    }

}
