<?php

namespace Puffer\Tests;

use Puffer\Puffer;
use Puffer\Exception;

class tester extends \PHPUnit_Framework_TestCase
{

    protected static $puffer,
        $storage,
        $conf = [];

    public function __construct()
    {
        if (!empty(self::$conf)) {
            return;
        }
        $vars = [
            'CONSUMER_KEY',
            'CONSUMER_SECRET',
            'ACCESS_TOKEN',
            'USER_ID',
            'PROFILE_ID',
            'UPDATE_ID',
            'USERNAME',
            'SHARE_URL',
            'SHARE_GREATER_THAN'
        ];
        $has_vars = true;
        foreach ($vars as $var) {
            if (($env = getenv('PUFFER_' . $var)) === FALSE) {
                $has_vars = false;
                self::$conf = [];
                break;
            } else {
                self::$conf[strtolower($var)] = ltrim($env, 'PUFFER_');
            }
        }
        if (!$has_vars) {
            $conf_file = __DIR__ . '/conf.php';
            if (!file_exists($conf_file)) {
                throw new Exception('Configuration file for tests was not found.');
            }
            self::$conf = require_once $conf_file;
        }
        if (!isset(self::$conf['storage']) and self::$storage !== null)
        {
            self::$conf['storage'] = self::$storage;
        }
    }

    public function setUp()
    {
        self::$puffer === NULL and self::$puffer = new Puffer(self::$conf);
    }

    public static function setStorage($storage)
    {
        self::$storage = $storage;
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
