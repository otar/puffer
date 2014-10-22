<?php

namespace Puffer\Traits;

trait Populate
{

    private function populate(array $data = [])
    {

        if (empty($data)) {
            throw new Exception('Can not populate a profile with an empty array.');
        } elseif (!isset($data['id'])) {
            throw new Exception('Data is corrupted, it doesn\'t have an "id" parameter.');
        }

        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

    }

}
