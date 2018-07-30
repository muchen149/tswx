<?php

namespace App\models\member;


use App\models\dct\DctBusineType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberWalletLog extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
    */
    protected $table = 'member_wallet_log';
    
    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 变更零钱
     */
    public function scopeChangeBalance($query, $busine_type, $data = array())
    {
        $db_prefix = config('database')['connections']['mysql']['prefix'];

        $data_log = array();
        $data_balance = array();
        $data_recharge = array();
        $data_cash = array();
        $update_data = "";
        $time = time();


        //查询用户零钱总账信息
        $member = Member::select('member_name', 'wallet_total', 'wallet_available', 'wallet_freeze')->where('member_id', $data['member_id'])->first()->toArray();

        //获得商城业务字典
        $busine_code = DctBusineType::select('code_id', 'code_name')->where('py_code', $busine_type)->first();

        $data_log['member_id'] = $data['member_id'];
        $data_log['member_name'] = $member['member_name'];
        $data_log['create_time'] = $time;
        $data_log['busine_id'] = $data['busine_id'];

        switch ($busine_type) {
            case 'LQCZ'://零钱充值
                $data_log['busine_type'] = $busine_code['code_id'];
                $data_log['av_amount'] = $data['balance_amount'];
                $data_log['freeze_amount']  = 0;
                $data_log['busine_content'] = $busine_code['code_name'] . '，充值单号: ' . $data['pay_sn'];
                $data_log['realtime_balance'] = $member['wallet_available'] + $data['balance_amount'];
                $data_log['realtime_freeze'] = $member['wallet_freeze'];

                $data_balance['wallet_available'] = array('+', $data['balance_amount']);
                $data_balance['wallet_total'] = array('+', $data['balance_amount']);

                $data_recharge['recharge_sn'] = $data['pay_sn'];
                $data_recharge['member_id'] = $data['member_id'];
                $data_recharge['member_name'] = $member['member_name'];
                $data_recharge['amount'] = $data['balance_amount'];
                $data_recharge['payment_code'] = $data['payment_code'];
                $data_recharge['payment_name'] = $data['payment_name'];
                $data_recharge['trade_sn'] = $data['trade_sn'];
                $data_recharge['payment_state'] = $data['payment_state'];
                $data_recharge['payment_time'] = $data['payment_time'];;
                $data_recharge['create_time'] = $time;
                break;
            case 'DJTXLQ':// 冻结提现部分零钱
                $data_log['busine_type'] = $busine_code['code_id'];
                $data_log['av_amount'] = $data['balance_amount'] * (-1);
                $data_log['freeze_amount'] = $data['balance_amount'];
                $data_log['busine_content'] = $busine_code['code_name'] . '，提现单号: ' . $data['pay_sn'];
                $data_log['realtime_balance'] = $member['wallet_available'] - $data['balance_amount'];
                $data_log['realtime_freeze'] = $member['wallet_freeze'] + $data['balance_amount'];

                $data_balance['wallet_available'] = array('-', $data['balance_amount']);
                $data_balance['wallet_freeze'] = array('+', $data['balance_amount']);

                $data_cash['cash_sn'] = $data['pay_sn'];
                $data_cash['member_id'] = $data['member_id'];
                $data_cash['member_name'] = $member['member_name'];
                $data_cash['amount'] = $data['balance_amount'];
                $data_cash['rechargecard_id'] = $data['rechargecard_id'];
                $data_cash['create_time'] = $time;
                break;
            case 'LQTX'://零钱提现
                $data_log['busine_type'] = $busine_code['code_id'];
                $data_log['av_amount'] = 0;
                $data_log['freeze_amount'] = $data['balance_amount'] * (-1);
                $data_log['busine_content'] = $busine_code['code_name'] . '，提现单号: ' . $data['pay_sn'];
                $data_log['realtime_balance'] = $member['wallet_available'];
                $data_log['realtime_freeze'] = $member['wallet_freeze'] - $data['balance_amount'];

                $data_balance['wallet_freeze'] = array('-', $data['balance_amount']);

                $data_cash['cash_sn'] = $data['pay_sn'];
                $data_cash['member_id'] = $data['member_id'];
                $data_cash['member_name'] = $member['member_name'];
                $data_cash['amount'] = $data['balance_amount'];
                $data_cash['rechargecard_id'] = $data['rechargecard_id'];
                $data_cash['create_time'] = $time;
                break;
            case 'DJPAYLQ'://冻结支付订单部分零钱【预支付】，可用减少，冻结增加，总额不变。
                $data_log['busine_type'] = $busine_code['code_id'];
                $data_log['av_amount'] = $data['balance_amount'] * (-1);
                $data_log['freeze_amount'] = $data['balance_amount'];
                $data_log['busine_content'] = $busine_code['code_name'] . '，可用减少，冻结增加，总额不变。';
                $data_log['realtime_balance'] = $member['wallet_available'] - $data['balance_amount'];
                $data_log['realtime_freeze'] = $member['wallet_freeze'] + $data['balance_amount'];

                $data_balance['wallet_available'] = array('-', $data['balance_amount']);
                $data_balance['wallet_freeze'] = array('+', $data['balance_amount']);
                break;
            case 'LQPAY'://零钱支付订单【确认支付】，可用不变，冻结减少，总额不变。
                $data_log['busine_type'] = $busine_code['code_id'];
                $data_log['av_amount'] = 0;
                $data_log['freeze_amount'] = $data['balance_amount'] * (-1);
                $data_log['busine_content'] = $busine_code['code_name'] . '，可用不变，冻结减少，总额不变。';
                $data_log['realtime_balance'] = $member['wallet_available'];
                $data_log['realtime_freeze'] = $member['wallet_freeze'] - $data['balance_amount'];

                $data_balance['wallet_freeze'] = array('-', $data['balance_amount']);
                break;
            case 'DDWZFCD':
                // DDWZFCD  2003【订单未（确认）支付撤单】；可用余额增加，冻结额度减少，总额度不变。
                $data_log['busine_type'] = $busine_code['code_id'];
                $data_log['av_amount'] = $data['balance_amount'];
                $data_log['freeze_amount'] = $data['balance_amount'] * (-1);
                $data_log['busine_content'] = $busine_code['code_name'] . '，可用余额增加，冻结额度减少，总额度不变。';
                $data_log['realtime_balance'] = $member['wallet_available'] + $data['balance_amount'];
                $data_log['realtime_freeze'] = $member['wallet_freeze'] - $data['balance_amount'];

                $data_balance['wallet_available'] = array('+', $data['balance_amount']);
                $data_balance['wallet_freeze'] = array('-', $data['balance_amount']);
                break;
            case 'DDTDTK':
                // DDTDTK   2004【订单（支付后）退单退款】；可用余额增加，冻结额度不变，总额度不变。
                $data_log['busine_type'] = $busine_code['code_id'];
                $data_log['av_amount'] = $data['balance_amount'];
                $data_log['freeze_amount'] = 0;
                $data_log['busine_content'] = $busine_code['code_name'] . '，可用余额增加，冻结额度不变，总额度不变。';
                $data_log['realtime_balance'] = $member['wallet_available'] + $data['balance_amount'];
                $data_log['realtime_freeze'] = $member['wallet_freeze'] ;

                $data_balance['wallet_available'] = array('+', $data['balance_amount']);
                break;
            default:
                Log::notice('变更零钱MemberWalletLog.php中scopeChangeBalance参数错误');
                return false;
                break;
        }

        foreach($data_balance as $column => $v){
            $update_data .= $column .'='. $column . $v[0] . $v[1] .',';
        }
        $update_sql = "UPDATE ".$db_prefix."member SET ".rtrim($update_data, ',')." WHERE member_id=".$data['member_id'];
        $update = DB::update($update_sql);

        if (!$update) {
            Log::notice('变更零钱MemberWalletLog.php中scopeChangeBalance方法更新member失败！');
            return false;

        }
        $insert = self::create($data_log);
        if (!$insert) {

            Log::notice('变更零钱MemberWalletLog.php中scopeChangeBalance方法插入member_wallet_log日志失败！');
            return false;

        }

        if(!empty($data_recharge)){
            $insert_recharge = DB::table('member_wallet_recharge')->insert($data_recharge);
            if (!$insert_recharge) {

                Log::notice('变更零钱MemberWalletLog.php中scopeChangeBalance方法插入member_wallet_recharge表失败！');
                return false;

            }
        }

        if(!empty($data_cash)){
            $insert_cash = DB::table('member_wallet_cash')->insert($data_cash);
            if (!$insert_cash) {

                Log::notice('变更零钱MemberWalletLog.php中scopeChangeBalance方法插入member_wallet_cash表失败！');
                return false;
            }
        }

        return $insert;
    }
    
}
