<?php

namespace App\pro\dao\member;

use App\models\member\Member;
use App\models\member\MemberShip;
use App\pro\dao\BaseDao;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MemberShipDao extends BaseDao
{
    /**
     * 获取还未使用的会员卡列表
     * @param int $member_id 用户id
     * @return array
     */
    public function getEnableCardByMemberId($member_id , $use_state=1)
    {
        // 查询各个不同等级下的商品数量信息(将平台定义的会员级别信息置于sql中了)
        $member_class_arr = $this->getMemberClass();
        $class_name_case_sql = ' (CASE grade';
        foreach ($member_class_arr as $member_class) {
            $class_name_case_sql .= ' WHEN ' . $member_class['grade_code'] . ' THEN "' . $member_class['class_name'] . '"';
        }

        $select_sql = "
            SELECT membership_id, activity_name, exp_date, exp_date_name, activity_images, start_time, end_time, close_time, price, use_state, grade,
            " . $class_name_case_sql . " END) AS class_name
            FROM " . $this->db_prefix . "member_ship
            WHERE member_id = ? AND use_state = ".$use_state."
            ORDER BY created_at DESC";

        return DB::select($select_sql, [$member_id]);
    }

    /**
     * 使用会员卡(通过扫码领取的会员卡)
     * @param int $membership_id 领取的会员卡记录
     * @param int $member_id 用户
     * @return bool
     */
    public function useCardByMemberShipId($membership_id, $member_id)
    {
        $cur_time = time();
        try {
            //  升级会员级别或续费
            $member = Member::find($member_id);
            $membership = MemberShip::find($membership_id);

            //未领取的截止日期并修给使用状态
            if($membership->close_time < $cur_time){
                $membership->use_state = -1;
                $membership->save();
                return false;
            }else{
                // Carbon插件方法的名字, 转换后如, 'year' => addYear、'month' => addMonth、 'day' => addDay // Carbon::now()->addMonth(1)->timestamp;
                $method_str = 'add' . ucfirst($membership->exp_date_code);

                // 同等级延长会员时间
                if ($membership->grade == $member->grade) {
                    $member->grade_expire_time = $member->grade_expire_time > $cur_time ?
                        Carbon::createFromTimestamp($member->grade_expire_time)->$method_str($membership->exp_date)->timestamp :
                        Carbon::now()->$method_str($membership->exp_date)->timestamp;
                    // 会员等级高于或低于会员卡等级两种情况(如果要限制现有会员等级高于会员卡等级使用的话, 在controller层做限制), 覆盖之前等级及过期时间
                } else {
                    $member->grade = $membership->grade;
                    $member->grade_expire_time = Carbon::now()->$method_str($membership->exp_date)->timestamp;
                }

                $membership->use_state = 2;
                $membership->updated_at = $cur_time;
                // 保存信息
                $member->save();
                $membership->save();
                return true;
            }

        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * 查询用户会员购买使用记录
     * @param int $member_id 用户id
     * @return array
     */
    public function getRecordByMemberIdForViewData($member_id)
    {
        // 查询各个不同等级下的商品数量信息(将平台定义的会员级别信息置于sql中了)
        $member_class_arr = $this->getMemberClass();
        $class_name_case_sql = ' (CASE grade';
        foreach ($member_class_arr as $member_class) {
            $class_name_case_sql .= ' WHEN ' . $member_class['grade_code'] . ' THEN "' . $member_class['class_name'] . '"';
        }

        $select_sql = "
            SELECT exp_date, exp_date_name, updated_at, price, created_at," . $class_name_case_sql . " END) AS class_name
            FROM " . $this->db_prefix . "member_ship
            WHERE member_id = ? AND use_state = 2
            ORDER BY updated_at DESC";

        return DB::select($select_sql, [$member_id]);
    }
}