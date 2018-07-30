<?php

namespace App\models\member;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\models\dct\DctBusineType;

class MemberYesbLog extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'member_yesb_log';

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
     *  当虚拟币业务发生时，调用此接口保存虚拟币收支明细日志并同步账户总额
     * 传入参数：
     * @param $busine_type_code     int     业务类型代码
     * @param $data                 array   业务数据，其中：member_id、busine_id，不能为空
     * 传出参数：
     * @param $insert              object   收支明细日志对象
     */
    public function scopeChangeBalance($query,$busine_type_code=0, $data=array(), $busine_content='')
    {
        $insert = null;
        if (count($data) == 0)
        {
            return $insert;
        }

        // 默认创建收支明细日志
        $is_create_log = 1;
        $data_log = array();
        $data_balance = array();

        $time = time();
        $db_prefix = config('database')['connections']['mysql']['prefix'];

        //查询用户零钱总账信息
        /* 当前用户虚拟币账户
        points_total    int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分总数【经验值】',
        yesb_total      decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '虚拟币总额',
        yesb_available  decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '虚拟币可用额',
        yesb_freeze     decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '虚拟币冻结额',
        */
        if (!array_key_exists("member_id",$data))
        {
            return $insert;
        }

        $member_id = (int)$data['member_id'];
        $member = Member::select('member_name','points_total',
            'yesb_total', 'yesb_available', 'yesb_freeze')
            ->where('member_id', $member_id)
            ->first()
            ->toArray();

        // 如果当前用户不存在，直接退出
        if(empty($member)){
            return $insert;
        }

        // 如果传入的业务代码不存在，直接退出
        $busine_type_code = (int)$busine_type_code;
        $busine_type = DctBusineType::select('code_id', 'code_name')
            ->where('code_id', $busine_type_code)
            ->first();
        if (!$busine_type)
        {
            return $insert;
        }

        // 业务内容及描述
        $busine_id = 0;
        $busine_content = trim($busine_content . '');
        if (!$busine_content)
        {
            $busine_content = $busine_type['code_name'];
            if (array_key_exists("busine_id", $data))
            {
                $busine_id = (int)$data['busine_id'];
                $busine_content .= '，业务ID为：' . $busine_id;
            }
        }

        /*
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
          `member_id` int(10) unsigned NOT NULL COMMENT '会员id',
          `member_name` varchar(60) DEFAULT '' COMMENT '会员名称',
          `busine_type` smallint(6) DEFAULT '0' COMMENT '业务类型【例如：邀请注册】',
          `busine_id` bigint(20) DEFAULT '0' COMMENT '业务ID【例如：邀请人ID】',
          `busine_content` varchar(250) NOT NULL DEFAULT '' COMMENT '业务说明【例如：注册邀请】',

           `create_time` int(11) DEFAULT NULL COMMENT '操作时间',
          `operater` int(11) DEFAULT '0' COMMENT '操作员ID',
        */
        $data_log['member_id'] = $member_id;
        $data_log['member_name'] = $member['member_name'];
        $data_log['busine_type'] = $busine_type_code;

        // 业务ID，由调用者提供，不能空
        $data_log['busine_id'] = $busine_id;
        $data_log['busine_content'] = $busine_content;
        $data_log['create_time'] = $time;
        $data_log['operater'] = $member_id;

        switch ($busine_type_code){
            case 12:
                // 12 抽奖获得
                /* 新增一条收入明细
                `yesb_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '虚拟币额【正数:收入;负数:支出】',
                */
                $data_log['yesb_amount'] = $data['yesb_amount'];

                // 总账户：可用增加、冻结不变
                $data_balance['yesb_total'] = array('+', $data['yesb_amount']);
                $data_balance['yesb_available'] = array('+', $data['yesb_amount']);
                break;
            case 13:
                // 13 抽奖花费
                /* 新增一条收入明细
                `yesb_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '虚拟币额【正数:收入;负数:支出】',
                */
                $data_log['yesb_amount'] = $data['yesb_amount'] * (-1);

                // 总账户：可用增加、冻结不变
                $data_balance['yesb_available'] = array('-', $data['yesb_amount']);
                break;
            case 2001:
                // 2001	订单预（冻结）支付
                // 明细账增加一条支出记录，状态为“冻结”
                /*
                `yesb_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '虚拟币额【正数:收入;负数:支出】',
                `is_freeze` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否冻结【0:否;1:是】，仅支出用'
                */
                $data_log['yesb_amount'] = $data['yesb_amount'] * (-1);
                $data_log['is_freeze'] = 1;

                // 总账户：可用减少、冻结增加
                $data_balance['yesb_available'] = array('-', $data['yesb_amount']);
                $data_balance['yesb_freeze'] = array('+', $data['yesb_amount']);
                break;
            case 2002:
                // 2002	订单确认支付
                // 原支付记录解冻【is_freeze 由1变更为0】,本业务仅解冻原有记录，不新增记录
                /*
                `yesb_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '虚拟币额【正数:收入;负数:支出】',
                `is_freeze` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否冻结【0:否;1:是】，仅支出用',
                `free_time` int(11) DEFAULT NULL COMMENT '解冻时间',
                */
                $is_create_log = 0;
                $data_log = array();

                // 读取原支付记录
                $insert = self::find((int)$data['id']);
                if ($insert)
                {
                    $insert->is_freeze = 0;
                    $insert->free_time = $time;
                    $insert->save();
                }

                // 总账户：可用不变、冻结减少
                $data_balance['yesb_freeze'] = array('-', $data['yesb_amount']);
                break;
            case 2003:
                // 2003	订单未（确认）支付撤单,
                // 原支付记录解除冻结
                $insert = self::find((int)$data['id']);
                if ($insert)
                {
                    $insert->is_freeze = 0;
                    $insert->free_time = $time;
                    $insert->save();
                }

                /* 新增一条退款记录
                `yesb_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '虚拟币额【正数:收入;负数:支出】',
                `related_id` bigint(20) DEFAULT '0' COMMENT '关联原记账记录ID【退账用】',
                */
                $data_log['yesb_amount'] = $data['yesb_amount'];
                $data_log['related_id'] = (int)$data['id'];

                // 总账户：可用增加、冻结减少
                $data_balance['yesb_available'] = array('+', $data['yesb_amount']);
                $data_balance['yesb_freeze'] = array('-', $data['yesb_amount']);
                break;
            case 2004:
                // 2004	订单（支付后）退单退款
                /* 新增一条退款记录
                `yesb_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '虚拟币额【正数:收入;负数:支出】',
                `related_id` bigint(20) DEFAULT '0' COMMENT '关联原记账记录ID【退账用】',
                */
                $data_log['yesb_amount'] = $data['yesb_amount'];
                $data_log['related_id'] = (int)$data['id'];
                $data_log['is_freeze'] = 0;

                // 总账户：可用增加、冻结不变
                $data_balance['yesb_available'] = array('+', $data['yesb_amount']);
                break;
            default:
                // throw new \Exception('虚拟币管理，输入参数错误');
                // 默认收支明细流水账，非冻结类型，例如：会员注册，奖励虚拟币
                /*
                `rule_id` smallint(6) DEFAULT '0' COMMENT '依你币规则ID',
                `rule_name` varchar(60) NOT NULL DEFAULT '' COMMENT '依你币规则名称',

                `yesb_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '虚拟币额【正数:收入;负数:支出】',
                `related_id` bigint(20) DEFAULT '0' COMMENT '关联原记账记录ID【退账用】',
                `is_points` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否同步为积分【经验值】（0:否;1:是;）',
                `points_amount` int(11) NOT NULL DEFAULT '0' COMMENT '积分额'
                */
                $data_log['rule_id'] = $data['rule_id'];
                $data_log['rule_name'] = $data['rule_name'];
                $data_log['yesb_amount'] = $data['yesb_amount'];
                $data_log['related_id'] = $data['related_id'];

                $data_log['is_points'] = $data['is_points'];
                $data_log['points_amount'] = $data['points_amount'];


                // 总账户：总额增加、可用增加、积分增加、冻结不变
                $yesb_amount = $data['yesb_amount'];
                if ($yesb_amount > 0)
                {
                    // 虚拟币总计是累计增加，没有减少操作
                    $data_balance['yesb_total'] = array('+', $yesb_amount);
                    $data_balance['yesb_available'] = array('+', $yesb_amount);
                }
                else
                {
                    $data_balance['yesb_available'] = array('-', abs($yesb_amount));
                }

                $points_amount = $data['points_amount'];
                if ($points_amount > 0)
                {
                    // 积分是累计增加，没有减少操作
                    $data_balance['points_total'] = array('+', $points_amount);
                }

                break;
        }

        // 1、更新虚拟币总账
        $update_data = "";
        foreach($data_balance as $column => $v){
            $update_data .= $column .'='. $column . $v[0] . $v[1] .',';
        }

        $update_sql = "UPDATE ".$db_prefix."member
                     SET ".rtrim($update_data, ',') . "
                     WHERE member_id = " . $member_id;
        $update = DB::update($update_sql);
        if (!$update) {
            throw new \Exception('操作失败');
        }


        // 2、收支明细增加一条记录
        if($is_create_log){
            $insert = self::create($data_log);
            if (!$insert) {
                throw new \Exception('操作失败');
            }
        }

        return $insert;
    }
}
