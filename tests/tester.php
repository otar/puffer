<?php

namespace Puffer\Tests;

use Puffer\Puffer;

class tester extends \PHPUnit_Framework_TestCase
{

    protected static $puffer,
        $config = [];

    public function __construct()
    {
        if (empty(self::$config)) {
            /*
            $params = [
                'consumer_key'
                'consumer_secret'
                'access_token'
                // IDs and specific user values for @BuffApiTest twitter user
                'tests' => [
                    'user_id' => '531d9b035a05ad7e6f000003',
                    'profile_id' => '5322f4f264f0894c5b24fe65',
                    'update_id' => '533ac0fe7088f53013aca435',
                    'username' => 'BuffApiTest',
                    'share' => [
                        'url' => 'https://news.ycombinator.com/',
                        'greater_than' => 500 // Last time I checked it was 510, lets floor it to 500
                    ]
                ]
            ];
            */
            self::$config = require_once 'config.php';
        }
    }

    public function setUp()
    {
        self::$puffer === NULL and self::$puffer = new Puffer(self::$config);
    }

    protected function assertArrayHasKeys($array = [], $keys = [])
    {
        foreach ($keys as $key) {
            if (empty($key)) {
                continue;
            }
            $this->assertArrayHasKey($key, $array);
        }
    }

    protected function assertObjectHasAttributes($object, $properties = [])
    {
        foreach ($properties as $property) {
            if (empty($property)) {
                continue;
            }
            $this->assertObjectHasAttribute($property, $object);
        }
    }

    protected function doTestProfile($profile)
    {
        $this->assertTrue($profile instanceof \Puffer\Profile);
        $this->assertArrayHasKeys((array) $profile, [
            '_id',
            'id',
            'default',
            'schedules',
            'service',
            'service_id',
            'user_id'
        ]);
    }

    protected function doTestUpdate($update)
    {
        $this->assertTrue($update instanceof \Puffer\Update);
        $items = [
            '_id',
            'id',
            'profile_id',
            'user_id',
            'text',
            'created_at'
        ];
        $this->assertArrayHasKeys($update, $items);
        $this->assertObjectHasAttributes($update, $items);
    }

}
