<?php

namespace App\Traits;

use Redis;

trait RedisTrait
{
    public function removeAllKey()
    {
        Redis::command('flushall');
        return;
    }

    public function getKey($key)
    {
        return \Cache::get($key);
    }

    public function deleteKey($key)
    {
        \Cache::forget($key);
        return;
    }
}
