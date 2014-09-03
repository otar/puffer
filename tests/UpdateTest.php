<?php

namespace Puffer\Tests;

use Puffer\Update;

class UpdateTest extends Tester
{

    protected static $update;

    protected function getUpdate()
    {
        null === self::$update and self::$update = new Update(self::$conf['update_id']);

        return self::$update;
    }

    //////////////////////////////

    public function test_update()
    {
        $update = $this->getUpdate();
        $this->doTestUpdate($update);
    }

}
