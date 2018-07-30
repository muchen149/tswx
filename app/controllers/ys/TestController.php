<?php
/**
 * 测试验证
 */
namespace App\controllers\ys;

use App\facades\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TestController extends BaseController
{
    public function index()
    {
        $return_info = array();
        $return_info['welcomeInfo'] = '欢迎您的访问，本页为易远达微商城测试页！';
        return view('test.index', compact('return_info'));
    }

    public function test(Request $request)
    {
        dd($request->all());
        $code = 0;
        $message = '';
        $data = null;

        // $data = $this->getPlatSetting('plat_points_rate');
        $data = $this->getPointsLimit(20, 19.98);

        return Api::responseMessage($code, $data, $message);
        // return view('test.index', compact('return_info'));
    }


    // 模拟登陆
    public function login(Request $request)
    {
        $member_id =(int)$request->input('member_id');
        if ($member_id <= 0)
        {
            // 没有传入会员信息，启用系统用户
            $member_id = 1;
        }

        Auth::loginUsingId($member_id);
        $user = Auth::user();
        $message = '用户' . $member_id . '登录';
        return Api::responseMessage(0, $user, $message);
    }

    // 显示当前登陆用户
    public function ShowLoginUser()
    {
        // dd(Auth::user());
        $code = 0;
        $user = Auth::user();
        if (!$user)
        {
            $code = 1;
        }

        $message = '当前登录用户信息';
        return Api::responseMessage($code, $user, $message);
    }
}