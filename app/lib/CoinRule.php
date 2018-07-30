<?php
/**
 * Created by PhpStorm.
 * User: shuo
 * Date: 16-9-8
 * Time: 下午3:05
 */
namespace App\lib;

use App\models\dct\DctBusineType;
use App\models\dct\DctYesbRule;

use App\models\member\Member;
use App\models\member\MemberGrowthSystem;
use App\models\member\MemberShareholdGrowth;
use App\models\member\MemberYesbLog;
use App\models\plat\PlatSetting;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 所有涉及到虚拟币（积分）的操作
 * @author      :lishuo
 * Class        :LogInfo
 * @package     :App\lib
 */
class CoinRule
{
    /**
     * 用户增加/减少虚拟币（积分），通过什么方式（$role_id）增加的（记录日志）
     * @param int $role_id      规则
     * @param int $member_id    当前用户【合法性由外部验证】
     * @param int $busine_type  业务类型【例如：邀请注册】
     * @param int $busine_id    业务ID【例如：邀请人ID】【在例如：评论加积分：评论人的ID】
     * @param int $related_id   关联原记账记录ID【退账用】
     * @param int $yesb_amount  虚拟币额，如果没有传入按照规则表中的数据添加
     * @param int $points_amount 积分额，如果没有传入按照规则表中的数据添加
     */
    public function add($role_id = 1, $member_id = 1,
                        $busine_type = 0, $busine_id = 0, $related_id = 0,
                        $yesb_amount = 0, $points_amount = 0)
    {
        $obj_member_yesb_log = null;
        try {
            DB::beginTransaction();

            // 获取积分的的规则(1虚拟币兑换多少积分)，例如：1虚拟币相当于100积分
            /*$plat_points_rate = 1;
            $obj_plat_points_rate = PlatSetting::where('name', 'plat_points_rate')->first();
            if ($obj_plat_points_rate)
            {
                $plat_points_rate = (int)($obj_plat_points_rate->value);
            }*/

            //获取虚拟币（积分）的规则
            $yesb_rule = DctYesbRule::where('rule_id', $role_id)->first();

            //业务规则
            $business_type = DctBusineType::where('code_id', $busine_type)->first();
            if ($yesb_rule && $business_type)
            {
                //规则存在并且当前规则是可用的
                if ($yesb_rule->is_use == 1)
                {
                    /* 积分规则(dct_yesb_rule)
                        points_amount int(11) NOT NULL DEFAULT '0' COMMENT '积分【经验值】数(可为负数)',
                        yesb_amount decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '虚拟币数(可为负数)',
                        is_use tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否可用【0:不可用;1:可用;】',
                    */
                    $data = array();
                    $data['member_id'] = $member_id;
                    $data['busine_id'] = $busine_id;
                    $busine_content = $business_type->code_name .  '，业务ID为：' . $busine_id;

                    // 业务涉及到的虚拟币数
                    $yesb_amount = ($yesb_amount == 0 ? (int)($yesb_rule->yesb_amount):$yesb_amount);
                    $data['yesb_amount'] = $yesb_amount;

                    // 关联原业务ID，例如退款等
                    $data['related_id'] = $related_id;

                    // 业务涉及到的积分数
                    $points_amount = ($points_amount == 0 ? (int)($yesb_rule->points_amount):$points_amount);
                    $data['points_amount'] = $points_amount;

                    $data['rule_id'] = $role_id;
                    $data['rule_name'] = $yesb_rule->rule_name;

                    // 是否同步为积分【经验值】（0:否;1:是;），默认不同步，只有获得积分时，才同步
                    $data['is_points'] = 0;
                    if ($points_amount != 0)
                    {
                        $data['is_points'] = 1;
                    }

                    // 更新虚拟币总账、保存收支明细
                    $obj_member_yesb_log = MemberYesbLog::ChangeBalance($busine_type, $data, $busine_content);
                }
            }

            // 添加虚拟币（积分）之后就需要更新用户级别，根据具体项目需求决定是否放开及调整
            // $this->grade($member_id);
            Log::info($member_id . '增加虚拟币（积分）');
            DB::commit();
        } catch (\Exception $e) {
            Log::info($member_id . '增加虚拟币（积分）失败！！！！');
            DB::rollBack();
            // dd('添加虚拟币（积分）方法错误');
        }

        return $obj_member_yesb_log;
    }


    /**
     * 根据member ID  更新用户的等级
     * 一般就在增加虚拟币（积分）和取消虚拟币（积分）的时候才会调用该方法
     * @param $id
     */
    public function grade($id)
    {
        // 会员类别（grade）【10:普通会员;20:黄金会员;30:钻石会员;40:黑卡VIP】',
        $moa = Member::where('member_id', $id)->first();
        //根据积分来 更新 等级 (暂时只有2个 普通会员 股东 才有等级)
        switch ($moa->grade) {
            case 10 :
                $mg = MemberGrowthSystem::where('min_points', '<=', $moa->points_total)
                    ->where('max_points', '>', $moa->points_total)
                    ->first();
                $moa->update([
                    "member_level_code" => $mg->level_code,
                    "member_level_name" => $mg->level_name,
                ]);
                break;
            case 20 :
                $mg = MemberShareholdGrowth::where('min_yesb', '<=', $moa->points_total)
                    ->where('max_yesb', '>', $moa->points_total)
                    ->first();
                $moa->update([
                    "sharehold_level_code" => $mg->level_code,
                    "sharehold_level_name" => $mg->level_name,
                ]);
                break;
        }
    }

}