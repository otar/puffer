<?php

// IMPORTANT! Account should not have pending updates, otherwise tests will fail

return [
    'consumer_key' => '',
    'consumer_secret' => '',
    'access_token' => '',
    'user_id' => '',
    'profile_id' => '',
    'update_id' => '', // id of an old, already sent update
    'username' => '',
    'share_url' => 'https://news.ycombinator.com/',
    'share_greater_than'=> 500 // last time i checked it was 510, lets floor it to 500
];
