<?php
/**
 * 测试验证
 */
namespace App\controllers;

use App\facades\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Foundation\Application;

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

        $soc = new SupplierOrderController();
        $soc ->thirdCreateOrder('465','2003');


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

    public function wxSendMsg(Request $request)
    {
        $sc=new StoreDeliverController();
        $sc->wxSendMsg(310,3);

        /*$app = new Application(config('wechat'));
        $notice = $app->notice;

        /*boolean setIndustry($industryId1, $industryId2) 修改账号所属行业；
        array getIndustry() 返回所有支持的行业列表，用于做下拉选择行业可视化更新；
        string addTemplate($shortId) 添加模板并获取模板ID；
        collection send($message) 发送模板消息, 返回消息ID；
        array getPrivateTemplates() 获取所有模板列表；
        array deletePrivateTemplate($templateId) 删除指定ID的模板。

        {{first.DATA}}
        订单号：{{keyword1.DATA}}
        订单金额：{{keyword2.DATA}}
        买家：{{keyword3.DATA}}
        订单状态：{{keyword4.DATA}}
        {{remark.DATA}}*    /

        $tt=$notice->getPrivateTemplates();

        $data = array(
            "first"    => "您有一个新的待发货订单",
            "keyword1" => "20170927001",
            "keyword2" => "39.8元",
            "keyword3" => "晨钟暮鼓",
            "keyword4"=> "已支付",
            "remark"   => "客户已付款，尽快发货吧！",
        );


        $messageId = $notice->send([
            'touser' => 'oWvmE0XS2lUqLJB2ZCEivirq7zQw',
            'template_id' => 'Zb0xNZPS7oW-XHqq2Hk9BJ9ZDM1NZ5Ywe5BBHj_mI90', //NWY3cjtE_puXk7elj9KVmjUvjTM9kHibI-19ruGVqf4    zs
            'url' => 'http://sdcd.shuitine.com/',
            'data' => $data,
        ]);


        $code = 0;
        $message = '';
        $data = null;
        return Api::responseMessage($code, $data, $message);
        // return view('test.index', compact('return_info'));*/
    }

}