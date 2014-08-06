<?php

namespace Puffer\Tests;

use Puffer\Profile;
use Puffer\Update;

class ProfileTest extends Tester
{

    private static $profile,
        $created_update;

    private function getProfile()
    {
        self::$profile === null and self::$profile = new Profile(self::$config['tests']['profile_id']);

        return self::$profile;
    }

    //////////////////////////////

    public function test_profile()
    {
        $profile = $this->getprofile();
        $this->doTestProfile($profile);
        $this->assertEquals(self::$config['tests']['profile_id'], $profile->id);
        $this->assertEquals(self::$config['tests']['username'], $profile->service_username);
        $this->assertEquals('twitter', $profile->service);
    }

    public function test_sent()
    {
        $sent = $this->getProfile()->sent();
        // Let's assume we have sent updates
        $this->assertTrue(!empty($sent));
        $this->doTestUpdate($sent[0]);
    }

    public function test_pending()
    {
        $pending = $this->getProfile()->pending();
        // Let's assume we DON'T have pending updates
        $this->assertTrue(empty($pending));
    }

    public function test_create()
    {
        $text = 'Some random number: ' . rand(999, 99999) . '.';
        $result = $this->getProfile()->create($text);
        $this->assertArrayHasKeys((array) $result, [
            'updates',
            'success',
            'message'
        ]);
        $this->assertEquals(1, $result->success);
        $this->assertTrue(isset($result->updates[0]));
        $this->doTestUpdate($result->updates[0]);
        $this->assertEquals($text, $result->updates[0]->text);
        self::$created_update = $result->updates[0];
    }

    /**
     * We should destroy updated created by an above method.
     * @depends test_create
     */
    public function test_destroy_created_update()
    {
        $this->assertTrue(self::$created_update instanceof \Puffer\Update);
        $result = self::$created_update->destroy();
        $this->assertTrue(is_object($result));
        $this->assertTrue(isset($result->success));
        $this->assertTrue((bool) $result->success === TRUE);
    }

}
