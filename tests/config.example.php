<?php

return [
    'consumer_key' => '',
    'consumer_secret' => '',
    'access_token' => '',
    'storage' => new \Puffer\Storages\Session,
    // IDs and specific user values for @BuffApiTest twitter user
    // IMPORTANT! Account should not have pending updates, otherwise tests will fail
    'tests' => [
        'user_id' => '531d9b035a05ad7e6f000003',
        'profile_id' => '5322f4f264f0894c5b24fe65',
        'update_id' => '533ac0fe7088f53013aca435', // Id of an old, already sent update
        'username' => 'BuffApiTest',
        'share' => [
            'url' => 'https://news.ycombinator.com/',
            'greater_than' => 500 // Last time I checked it was 510, lets floor it to 500
        ]
    ]
];
