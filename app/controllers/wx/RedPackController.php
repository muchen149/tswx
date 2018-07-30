<?php

namespace App\controllers\wx;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\models\company\CompanyPayAccount;
use App\models\member\MemberAwardsRecord;
use App\models\member\MemberPublicInfo;
use App\models\company\CompanyBaseinfo;
use EasyWeChat\Foundation\Application;
use App\models\goods\GoodsSku;

class RedPackController extends Controller
{
    /**
     * api端调用发送红包
     */
    public function sendRedPack4Subscribe()
    {
        if (isset($_POST['openid'])) {
            if (!empty($_POST['openid'])) {
                $hf_openid = $_POST['openid'];
                $this->sendRedPackByOpenid($hf_openid);
            }
        }
    }

    /**
     * 本平台公众号发送红包和开放平台代公众号发送红包调用该方法
     * @param $openid
     */
    public function sendRedPackByOpenid($openid)
    {
        $member_info = MemberPublicInfo::where('openid', $openid)->first();
        if ($member_info) {
            $member_id = $member_info->member_id;
            $member_cmpid = $member_info->company_id;

            $awardRecord = MemberAwardsRecord::select(
                'awardsrecord_id', 'company_id', 'activity_id', 'order_id', 'activity_type',
                'prize_level', 'prize', 'exchange_type', 'prize_type', 'exchange_state'
            )
                ->where('member_id', $member_id)
                ->where('company_id', $member_cmpid)
                ->where('exchange_state', 0)
                ->first();

            if ($awardRecord) {
                $awardsrecord_id = $awardRecord->awardsrecord_id;
                $res_data = $this->autoSendRedpack($member_info, $awardRecord);

                if ($res_data['code'] == 0) {
                    MemberAwardsRecord::where('awardsrecord_id', $awardsrecord_id)->update(['exchange_state' => 1]);
                }
            }
        }
    }

    /**
     * easyWechat 发红包
     * @return mixed
     */
    private function autoSendRedpack($member_info, $awardRecord)
    {
        $member_info->member_id;

        // 第三方支付发放红包处理,根据配置表中的信息进行第三方公众号支付信息发放红包 20170525
        if (!empty($awardRecord['company_id'])) {
            $company = CompanyBaseinfo::select('company_name')->where('company_id', $awardRecord['company_id'])->first();
        }

        $value = empty($awardRecord['company_id']) ? 0 : $awardRecord['company_id'];
        //根据中奖记录中的红包对应sku_id 活动红包金额
        $redpackInfo=GoodsSku::where('sku_id',$awardRecord['prize'])->first();
        $price=$redpackInfo->price;
        $luckyMoneyData = [
            'mch_billno' => date("YmdHis") . rand(1000, 9999),
            'send_name' => isset($company) && !empty($company['company_name']) ? $company['company_name'] : '水丁网',
            're_openid' => $member_info['openid'],
            'total_num' => 1,  // 普通红包固定为1，裂变红包不小于3
            'total_amount' => $price * 100,  // 单位为分，普通红包不小于100，裂变红包不小于300
            'wishing' => "码上有礼，好运连连！",
            'act_name' => (isset($company) && !empty($company['company_name']) ? $company['company_name'] : '水丁网') . $awardRecord['activity_name'] . "活动",
            'remark' => '',
        ];

        // 查询支付商户号信息
        $payAccount = CompanyPayAccount::where('company_id', $value)->first();
        if ($payAccount) {
            $merchant_id = $payAccount->merchant_id;
            $pay_key = $payAccount->key;

            $cert_path_t = 'wx/cert/' . ($value == 0 ? '' : $value . '/') . 'apiclient_cert.pem';
            $key_path_t = 'wx/cert/' . ($value == 0 ? '' : $value . '/') . 'apiclient_key.pem';

            $cert_path = public_path($cert_path_t);
            $key_path = public_path($key_path_t);

            // 配置微信红包参数
            $app_id = $member_info['authorizer_appid'];

            $options = [
                'app_id' => $app_id,

                'payment' => [
                    'merchant_id' => $merchant_id,
                    'key' => $pay_key,
                    'cert_path' => $cert_path,
                    'key_path' => $key_path,
                    'notify_url' => 'http://sdwx.shuitine.com/wx/wxCallback',
                ],
            ];

            $result = (new Application($options))->lucky_money->sendNormal($luckyMoneyData);

        } else {
            // 没有支付商户号信息
            return array('code' => 50002, 'message' => '公众号未配置商户号信息');
        }

        if ($result['return_code'] == "SUCCESS" && $result['result_code'] == "SUCCESS") {
            return array('code' => 0);
        } else {
            return array('code' => 50002, 'message' => $result['return_msg']);
        }
    }

}