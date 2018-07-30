<?php

namespace App\facades;

use Illuminate\Support\Facades\Facade;

class LogInfoFacade extends Facade
{
    /**
     * 返回类的键值
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'loginfo';     
    }
}
    