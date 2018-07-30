<?php
/**
 * 用户【会员】管理
*/
namespace App\controllers\jiudian;

use App\facades\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController  extends BaseController
{
    public function getLoginUserInfo()
    {
        dd(Auth::user());
    }

    public function logOut()
    {
        echo '用户' . Auth::user()->member_name . '退出登录';
        Auth::logout();
    }
}