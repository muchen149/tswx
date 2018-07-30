<?php

namespace App\facades;

use Illuminate\Support\Facades\Facade;

/**
 * 物流  
 * @author      :lishuo
 * Class        :LogInfoFacade
 * @package     :App\facades
 */
class Logistics extends Facade
{
    /**
     * 返回类的键值
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'logistics';     
    }
}
    