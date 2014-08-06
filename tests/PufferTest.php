<?php

namespace Puffer\Tests;

class PufferTest extends Tester
{

    public function test_is_authorized()
    {
        $is_authorized = self::$puffer->isAuthorized();
        $this->assertTrue($is_authorized);
    }

    public function test_get_auth_url()
    {
        $url = self::$puffer->getAuthUrl();
        $check = filter_var($url, FILTER_VALIDATE_URL);
        $this->assertNotFalse($check);
    }

    public function test_user()
    {
        $user = (array) self::$puffer->user;
        $this->assertArrayHasKeys($user, [
            '_id',
            'id',
            'name'
        ]);
        $this->assertEquals($user['id'], self::$config['tests']['user_id']);
    }

    public function test_configuration()
    {
        $configuration = (array) self::$puffer->configuration;
        $this->assertArrayHasKeys($configuration, [
            'services',
            'media'
        ]);
    }

    public function test_shares()
    {
        extract(self::$config['tests']['share']);
        $shares = self::$puffer->shares($url);
        $this->assertArrayHasKey('shares', $shares);
        $this->assertGreaterThan($greater_than, $shares['shares']);
    }

}
