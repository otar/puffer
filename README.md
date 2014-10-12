Puffer for Buffer
=========================

[![Latest Stable Release](https://poser.pugx.org/otar/puffer/v/stable.svg)](https://packagist.org/packages/otar/puffer) [![Build Status](https://travis-ci.org/otar/puffer.svg?branch=master)](https://travis-ci.org/otar/puffer) [![License](https://poser.pugx.org/otar/puffer/license.svg)](https://github.com/otar/puffer/blob/master/LICENSE)

Puffer is a [Buffer API][1] wrapper library for PHP.

Full documentation coming soon. Meanwhile take a look at [Basic Usage][2] and [Basic Docs][3] sections below. Also dig deeper into the source code, aren't we hackers? :)

----------

Installation
------------
Best way to get Puffer up and running is [Composer][4]. Include `otar/puffer` in your composer.json requirements:

```json
{
    "require": {
        "otar/puffer": "1.*"
    }
}
```

Alternatively you can grab code from this repository, but you will have to manually install dependencies in the `vendor/` directory and take care of PSR-4 autoloading.

----------

Basic Usage
-----------

Initialize & Authorize

```php
<?php

use Puffer\Puffer;

$puffer = new Puffer([
    'consumer_key' => 'YOUR_CONSUMER_KEY_HERE',
    'consumer_secret' => 'YOUR_CONSUMER_SECRET_HERE',
    'access_token' => 'YOUR_ACCESS_TOKEN_HERE', // If you have one, or authorization will be required
    'storage' => new \Puffer\Storages\Session // Stores tokens in the session (default). Implement Puffer\StorageInterface class to save tokens in the database.
]);

if (!$puffer->isAuthorized()) {
    header('Location: ' . $puffer->getAuthUrl());
    exit;
}

var_dump($puffer->user); // Get user settings
```


----------


List Your Profiles

```php
<?php

use Puffer\Profiles;

$profiles = new Profiles;

var_dump($profiles->all()); // All profiles

// Also Profiles object can be accessed as an array,
$first_profile = $profiles[0];

// be counted,
$number_of_profiles = count($profiles);

// or even be iterated.
foreach ($profiles AS $profile) {
    $profile->create('Hello World');
}
```

----------

List Pending Updates

```php
<?php

use Puffer\Profiles;

// Grab first profile and it's pending updates in one line.
$pending = (new Profiles)[0]->pending();
```

Create An Update

```php
<?php

use Puffer\Profile;

$profile = new Profile('YOUR_PROFILE_ID_HERE');
$result = $profile->create('Hello World');

if ((bool) $result->success) {
    echo 'One more update buffered in your queue.';
} else {
    echo 'Something went wrong.';
}

// Or like this:

$result = (new Profile('YOUR_PROFILE_ID_HERE'))->create('Hello World');
```

----------

Delete Pending Update

```php
<?php

use Puffer\Profiles;
use Puffer\Update;

// If you have an update id, then:
$update = new Update('UPDATE_ID_HERE');
$update->delete();

// Or grab first profile and delete first pending update in one line:

$result = (new Profiles)[0]->pending()[0]->destroy();
```

----------

Basic Docs
------------
Library is contextually separated into four major classes:

 - Puffer
     - Used for initialization, auth and making calls to the API.
     - Access `user` and `configuration` object properties for related results from `/user` and `/info/configuration`.
     - `shares()` accepts link as an argument and return it's number of shares through Buffer platform.
 - Profiles
     - Retrieves all profiles under your account.
     - Use `all()` method for returning profiles as an array.
     - Profiles can be accessed as an array, iterated in the loops or counted using PHP's `count` function.
     - All profiles are returned as an objects that have their own functionality. See Profile description below.
 - Profile
     - Accepts profile ID as an argument.
     - Profile data can be accessed from the objects' properties or as an associative array.
     - `sent()` method returns already sent updates from this profile and `pending()` method returns currently buffered/pending updates.
     - You can create/buffer an update directly from the `create()` method. Accepts text as an argument.
 - Update
     - Accepts update ID as an argument.
     - Update data can be accessed from the objects' properties or as an associative array.
     - Supports these methods: `edit()`, `share()`, `interactions()`, `destroy()` and `moveToTop()`.

> It's highly recommended to wrap your Puffer code in the `try/catch`
> block, it will throw an `Puffer\Exception` in case of errors.

----------

Contributing
------------

If you want to contribute to this project first and the foremost you'll need to setup local development environment.

I encourage you to use [GruntJS][5]. You'll need to install these NodeJS modules (I'll probably switch to Bower in the future) from the [NPM][6]:

 - grunt-phplint
 - grunt-php-cs-fixer
 - grunt-phpunit
 - grunt-contrib-watch
 - grunt-notify

After installing GruntJS plugins, simply run the `grunt` command. It will begin to "watch" modifications in the `src/` and `tests/` directories and run Grunt tasks as soon as you change something in the `*.php` files.

> For testing you may prefer to use local PHPUnit installation, please make sure it's updated to the latest version.

First create an issue, fork a repository, make changes and make "pull request".

----------

Final Notes
------
Best way to reach me is an email or Twitter. See my contact information here: [http://otar.me][7]

And to help you finish reading this stuff in a good mood here's a joke:

```
Husband: make me a sandwich.
Wife: what? make it yourself!
Husband: sudo make me a sandwich.
Wife: okay...
```

Thanks for staying tuned! :)

  [1]: https://bufferapp.com/developers/api
  [2]: #basic-usage
  [3]: #basic-docs
  [4]: https://getcomposer.org/
  [5]: http://gruntjs.com/
  [6]: https://www.npmjs.org/
  [7]: http://otar.me/
