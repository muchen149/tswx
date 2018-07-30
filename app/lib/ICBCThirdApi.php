<?php

namespace App\lib;

use App\icbc\AES;
use App\icbc\DefaultIcbcClient;
use App\icbc\IcbcCa;
use App\icbc\IcbcConstants;
use App\icbc\IcbcEncrypt;
use App\icbc\IcbcSignature;
use App\icbc\RSA;
use App\icbc\UiIcbcClient;
use App\icbc\WebUtils;

use Illuminate\Support\Facades\Log;

/**
 * 工行e生活接口
 *
 */
class ICBCThirdApi
{

    static public function queryChangedGoodsInfo($startTime,$endTime)
    {

    }

}