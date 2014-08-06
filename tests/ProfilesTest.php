<?php

namespace Puffer\Tests;

use Puffer\Profiles;

class ProfilesTest extends Tester
{

    private static $profiles = [];

    private function getProfiles()
    {
        empty(self::$profiles) and self::$profiles = new Profiles;

        return self::$profiles;
    }

    //////////////////////////////

    public function test_profiles()
    {
        $profiles = $this->getProfiles();
        $this->assertTrue($profiles instanceof \Iterator);
        $this->assertTrue($profiles instanceof \ArrayAccess);
        $this->assertTrue($profiles instanceof \Countable);
    }

    /**
     * @depends test_profiles
     */
    public function test_first_profile()
    {
        $profiles = $this->getProfiles();
        $this->assertTrue(isset($profiles[0]));
        $this->doTestProfile($profiles[0]);
        $this->assertEquals($profiles[0]->id, self::$config['tests']['profile_id']);
        $this->assertEquals($profiles[0]->user_id, self::$config['tests']['user_id']);
        $this->assertTrue((bool) $profiles[0]->default);
    }

    /**
     * @depends test_profiles
     */
    public function test_profiles_iteration()
    {
        $profiles = $this->getProfiles();
        $count = 0;
        foreach ($profiles as $profile) {
            $count++;
            $this->doTestProfile($profile);
        }
        $this->assertEquals(1, $count);
    }

    /**
     * @depends test_profiles
     */
    public function test_count()
    {
        $profiles = $this->getProfiles();
        $this->assertEquals(1, count($profiles));
    }

    /**
     * @depends test_profiles
     * @depends test_first_profile
     * @depends test_count
     */
    public function test_all()
    {
        $profiles = $this->getProfiles();
        $this->assertEquals($profiles[0], $profiles->all()[0]);
    }

    /**
     * @depends test_all
     */
    public function test_unset()
    {
        $profiles = $this->getProfiles();
        $this->assertTrue(isset($profiles[0]));
        unset($profiles[0]);
        $this->assertFalse(isset($profiles[0]));
    }

}
