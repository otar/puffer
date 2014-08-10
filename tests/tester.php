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
        $items = [
            '_id',
            'id',
            'default',
            'schedules',
            'service',
            'service_id',
            'user_id'
        ];
        $this->assertArrayHasKeys($profile, $items);
        $this->assertObjectHasAttributes($profile, $items);
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
