<?php

namespace App\facades;

use Illuminate\Support\Facades\Facade;

class CoinRuleFacade extends Facade
{
    /**
     * 返回类的键值
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'coin_rule';     
    }
}
    