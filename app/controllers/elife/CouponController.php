<?php
/**
 * e生活首页
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/27 0027
 * Time: 上午 9:44
 */

namespace App\controllers\elife;

use App\facades\Api;
use App\models\elife\CouponActivity;
use App\models\elife\CouponShip;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CouponController extends BaseController
{
    public function couponActive()
    {
        $member_id = $this->getLoginUserId();
        /*$couponActivity = CouponActivity::join('coupon_ship as a','a.activity_id','=','coupon_activity.activity_id')
                                            ->where('a.member_id',$member_id)
                                            ->get();*/
        $couponActivity = CouponActivity::get();
        foreach($couponActivity as $coupon){
            $couponShip = CouponShip::where('member_id',$member_id)->where('activity_id',$coupon->activity_id)->first();
            if($couponShip){
                $coupon->draw = 1;
            }else{
                $coupon->draw = 0;
            }
        }
        return view("elife.coupon_active", compact('couponActivity'));
    }

    /**
     * 优惠劵领取
     * @param Request $request
     * return array
     */
    public function ajax_couponDraw(Request $request)
    {
        $member_id = $this->getLoginUserId();

        $activity_id = (int)($request->input('activity_id'));

        $coupon = CouponActivity::where('activity_id', $activity_id)->where('activity_state', 1)->first();
        if (!$coupon) {
            return Api::responseMessage(1, '', '参数非法');
        }
        $couponShip = CouponShip::where('activity_id', $activity_id)->where('member_id', $member_id)->where('source_type', 2)->first();

        if (!$couponShip) {

            DB::beginTransaction();
            $couponShip = CouponShip::create([
                'member_id' => $member_id,
                'activity_id' => $coupon->activity_id,
                'activity_name' => $coupon->activity_name,
                'activity_images' => $coupon->activity_images,
                'start_time' => $coupon->start_time,
                'end_time' => $coupon->end_time,
                'price' => $coupon->price,
                'source_type' => 2, // 来源类型: 2：线上开通购买
                'use_state' => 1,   // 使用状态: 0:暂不可使用(预付单, 暂不可使用)
                'created_at' => time(),
                'updated_at' => 0,
            ]);
            if ($couponShip) {
                $result = array('code' => 0,'message' => '领取成功');
                if ($result['code'] == 0) {
                    DB::commit();
                    return Api::responseMessage($result['code'],'',$result['message']);
                } else {
                    DB::rollBack();
                    return Api::responseMessage(2, '', '不可重复领取');
                }
            }

        }

        return Api::responseMessage(2, '', '不可重复领取');

    }
	//优惠券列表
	public function lista() {
		echo 123;die;
	}

}
