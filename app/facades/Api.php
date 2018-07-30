<?php

namespace App\facades;

use Illuminate\Support\Facades\Facade;

class Api extends Facade
{
    /**
     * 返回类的键值
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'api';
    }
}
    