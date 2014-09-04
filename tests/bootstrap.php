<?php

namespace Puffer\Tests;

use Puffer\Storages\Session;

$loader = require_once dirname(__DIR__) . '/vendor/autoload.php';
$loader->add('Puffer\\Tests\\', __DIR__);

require_once 'tester.php';

tester::setStorage(new Session);
