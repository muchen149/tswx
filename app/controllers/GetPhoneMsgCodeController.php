<?php
namespace App\controllers;

use App\facades\Api;
use App\lib\PhoneMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GetPhoneMsgCodeController extends BaseController
{
    /**
     * 获取手机短信验证码
     * @param Request $request
     * @return Api
     */
    public function getMsgCode(Request $request)
    {
        $phoneNum = $request->input('login_phonenum');

        // 验证手机号的合法性
        if (PhoneMessage::checkPhoneNumRegEx($phoneNum)) {
            return Api::responseMessage(2, '', "手机号格式不对！");
        }

        if ($this->sendPhoneMessage($phoneNum)) {
            return Api::responseMessage(1, '', "获取验证码失败！");
        } else {
            return Api::responseMessage(0, '', "获取验证码成功！");
        }
    }


    /**
     * 发送手机短信
     * @param string $phoneNum 手机号，合法性由调用者验证
     * @param string $strInfo 发送消息，如果为空，默认发送验证码
     * @return int $return_value 0成功；1出错
     */
    public function sendPhoneMessage($phoneNum, $strInfo = '')
    {
        $return_value = 0;

        // 如果没有传入发送信息，默认发送6位的验证码
        $strInfo = trim($strInfo . '');
        if ($strInfo == '') {
            $strInfo = PhoneMessage::creatMsgCode(6);
            Cache::put('PhoneMsgCode', md5($strInfo), 2);
        }

        // 发送验证码到手机
        $retMsg = '';
        Cache::put('PhoneNumber', $phoneNum, 2);
        if (PhoneMessage::sendPhoneMessage($phoneNum, $strInfo, $retMsg)) {
            // 发送失败，返回1
            $return_value = 1;
        }

        return $return_value;
    }
}



