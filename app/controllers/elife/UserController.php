<?php
/**
 * 用户【会员】管理
 */
namespace App\controllers\elife;
use App\facades\Api;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use llluminate\Support\Facades\input;
use App\models\member\Member;
use App\models\member\MemberElifeExtend;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\icbc\IcbcEncrypt;
use App\icbc\DefaultIcbcClient;
use App\icbc\IcbcConstants;

class UserController  extends BaseController
{
    public $DefaultIcbcClient;
	/**
	 * 工银E生活授权登录 分行窗口
	 */
	public function getLoginUserInfo(Request $request) {
		//e生活登录授权数据
		$getRequestInfo = $request->input("loginParams");
		//跳转公共窗口方法
		$return = $this->publicEntryAdd($getRequestInfo,1);

		return $return;
    }

	/**
	 * 工银E生活授权登录 总行窗口1
	 */
	public function getLoginUserInfoOne(Request $request) {
		//e生活登录授权数据
		$getRequestInfo = $request->input("loginParams");
		//跳转公共窗口方法
		$return = $this->publicEntryAdd($getRequestInfo,2);

		return $return;
    }

	/**
	 * 工银E生活授权登录 总行窗口2
	 */
	public function getLoginUserInfoTwo(Request $request) {
		//e生活登录授权数据
		$getRequestInfo = $request->input("loginParams");
		//跳转公共窗口方法
		$return = $this->publicEntryAdd($getRequestInfo,3);

		return $return;	
    }
	
	/**
	 * 公共过滤方法
	 * @param  $getRequestInfo(过滤数据) $number(窗口类型1:分行2:总行1 3:总行2)
	 * @time   2018-06-13
	 * @author MUCHEN
	 */ 
	public function publicEntryAdd($getRequestInfo = '',$number = '') {
		if (!$getRequestInfo) {
			return Api::responseMessage(10001,'','参数无效或为空');
		}
		$data = $this->aesDecrypt($getRequestInfo);
		if (!empty($data['cust_id']) || !empty($data['phone']) || !empty($data['device_id']) || !empty($data['currentTimeMillis'])) {
            $member = DB::table('member')->where('cust_id',$data['cust_id'])->first();
            $elife_member_extend = DB::table('elife_member_extend')->where('cust_id',$data['cust_id'])->first();
			//通过elife接口进行判断
			$member_name = 'elife'.time().rand(0,999);
            if (!$member || !$elife_member_extend) {
				DB::table('member')->where('cust_id',$data['cust_id'])->delete();
				DB::table('elife_member_extend')->where('cust_id',$data['cust_id'])->delete();
				if (empty($data['nick_name'])) {
					$data['nick_name'] = $member_name;
				}
				//用户基本信息表
				DB::table('member')->insert([
				'member_name' => $member_name,
				'grade'       => 20,
				'nick_name'   => $data['nick_name'],
				"login_ip"    => Api::getIp(),
				"mobile"      => $data['phone'],
				"regist_time" => time(),
				"login_time"  => time(),
				"login_num"   => 1,
				"cust_id"     =>$data['cust_id'],
				'entrance_type'		 =>$number,
				]);
				//工商elife扩展表 type:1分行 2总行窗口1 3总行窗口2
				DB::table('elife_member_extend')->insert([
				'cust_id'    => $data['cust_id'],
				'phone'      => $data['phone'],
				'longitude'  => !empty($data['longitude']) ? $data['longitude'] : 'null_data',
				'latitude'   => !empty($data['latitude']) ? $data['latitude'] : 'null_data',
				'cisno'      => !empty($data['cisno']) ? $data['cisno'] : 'null_data',
				'third_cisno'=> !empty($data['third_cisno']) ? $data['third_cisno'] : 'null_data',
				'special'    => !empty($data['special']) ? $data['special'] : 'null_data',
				'isNewUser'  => $data['isNewUser'],
				'city_code'  => $data['city_code'] ? $data['city_code'] : 110100,
				'city_name'  => !empty($data['city_name']) ? $data['city_name'] : 'null_data',
				'device_id'  => $data['device_id'],
				'currentTimeMillis'=> $data['currentTimeMillis'],
				'real_name'  => !empty($data['real_name']) ? $data['real_name'] : 'null_data',
				'ID_card'    => !empty($data['ID_card']) ? $data['ID_card'] : 'null_data',
				'nick_name'  => $data['nick_name'],
				'photo'      => !empty($data['photo']) ? $data['photo'] : 'null_data',
				]);
				$member = DB::table('member')->where('cust_id',$data['cust_id'])->first();
            } else {
				//更新同步用户手机号 (member,elife_member_extend)
				if ($data['phone'] <> $member->mobile) {
					$saveMobile = DB::table('member')->where('cust_id',$data['cust_id'])->update(array("mobile"=>$data['phone']));
				}
				if ($data['phone'] <> $elife_member_extend->phone) {
					$savePhone = DB::table('elife_member_extend')->where('cust_id',$data['cust_id'])->update(array("phone"=>$data['phone']));
				}
				if ($member->entrance_type <> $number) {
					$savePhone = DB::table('member')->where('cust_id',$data['cust_id'])->update(array("entrance_type"=>$number));
				}
                $member_obj = Member::find($member->member_id);
                $member_obj->login_num += 1;
                $member_obj->old_login_time = $member_obj->login_time;
                $member_obj->old_login_ip = $member_obj->login_ip;
                $member_obj->login_time = time();
                $member_obj->login_ip = Api::getIp();
                $member_obj->save();
                if($member_obj->old_login_time != 0){
					//修改用户状态 (1、新用户 0、老用户)
                   	DB::table('elife_member_extend')->where('cust_id',$data['cust_id'])->update(array('isNewUser'=>0));
                }
			}
				//存储用户信息
				Auth::loginUsingId($member->member_id);
				//根据数字类型进行跳转
				return redirect('elife/eLifeIndex');
        } else {
			return Api::responseMessage(10001,'','参数无效或为空');
        }
	}
}