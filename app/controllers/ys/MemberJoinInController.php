<?php

namespace App\controllers\ys;

use App\facades\Api;
use App\lib\ApiResponseByHttp;
use App\models\marketing\GiftActivity;
use App\models\marketing\GiftActivityPackage;
use App\models\marketing\GiftCard;
use App\models\marketing\GiftPackage;
use App\models\marketing\GiftPackageGoods;
use App\models\member\Member;
use App\models\dct\DctArea;

use App\models\member\MemberGiftCoupon;
use App\models\member\MemberGiftCouponGoods;
use App\models\member\MemberJoinIn;
use App\models\member\MemberSupply;

use App\models\member\MemberRechargeCard;
use App\models\qrcode\TwoDimensionCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\models\order\Order as PlatOrder;
use Illuminate\Http\Request;

/**
 * 用户申请控制器
 */
class MemberJoinInController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 用户中心-》我的礼券
     * 传入参数：
     * @return mixed
     */
    public function myJoininList()
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        //获取用户申请列表
        $is_apply_group = $is_apply_proxy = false;
        $groupList = $proxyList = [];
        $memberJoininList = MemberJoinIn::where('member_id', $member_id)->orderBy('created_at', 'desc')->get();
        foreach ($memberJoininList as $joinin) {
            $joinin->apply_grade_name = $joinin->apply_grade == 20 ? '团采商用户' : '代理商用户';
            switch ($joinin->verify_state) {
                case 0:
                    $joinin->state_name = '已取消';
                    break;
                case 1:
                    $joinin->state_name = '未审核';
                    break;
                case 2:
                    $joinin->state_name = '已通过';
                    break;
                case -1:
                    $joinin->state_name = '申请驳回';
                    break;
            }
            if ($joinin->apply_grade == 20) {
                $groupList[] = $joinin;
                if ($joinin->verify_state == 1 || $member->grade == 20) {
                    $is_apply_group = true;
                }
            }
            if ($joinin->apply_grade == 30) {
                $proxyList[] = $joinin;
                if ($joinin->verify_state == 1 || $member->grade == 30) {
                    $is_apply_proxy = true;
                }
            }
        }
        return view('user.my_joinin_list', compact('memberJoininList',
            'is_apply_group', 'is_apply_proxy', 'groupList', 'proxyList'));
    }

    /**
     * 用户中心-》我的礼券
     * 传入参数：
     * @return mixed
     */
    public function joininDetail($id)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }
        //获取用户申请列表
        $memberJoinin = MemberJoinIn::where('member_id', $member_id)->find($id);
        switch ($memberJoinin->verify_state) {
            case 0:
                $memberJoinin->state_name = '已完成';
                break;
            case 1:
                $memberJoinin->state_name = '申请待审核';
                break;
            case 2:
                $memberJoinin->state_name = '申请取消';
                break;
            case -1:
                $memberJoinin->state_name = '申请关闭';
                break;
        }
        return view('user.my_joinin_detail', compact('memberJoinin'));
    }

    /**
     * 用户中心-》我的礼券
     * 传入参数：
     * @return mixed
     */
    public function cancelApply(Request $request)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return Api::responseMessage(5000, '', '登陆信息失效，请重新登陆');
        }

        $id = $request->input('id');
        //获取用户申请列表
        $memberJoinin = MemberJoinIn::where('member_id', $member_id)->find($id);
        if ($memberJoinin && $memberJoinin->verify_state == 1) {
            $memberJoinin->verify_state = 0;
            $memberJoinin->updated_at = time();
            $memberJoinin->save();
        } else {
            return Api::responseMessage(5000, '', '申请信息不存在');
        }
        return Api::responseMessage(0, '', '取消成功');
    }

    /**
     * 完善申请信息
     * 传入参数：
     * @return mixed
     */
    public function submitApply(Request $request)
    {
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        $applyType = $request->input('applyType');
        $uploadPic = $request->input('uploadPic');
        $companyName = $request->input('companyName');
        $companyPhone = $request->input('companyPhone');
        $apply_grade = $applyType == 'groupbuy' ? 20 : 30;

        $uploadPic = explode(',', $uploadPic);
        $member = Member::find($member_id);
        if ($member && $member->grade != $apply_grade) {
            $joinin = MemberJoinIn::where('member_id', $member_id)
                ->where('verify_state', 1)->first();
            if (!$joinin) {
                MemberJoinIn::create([
                    'member_id' => $member_id,
                    'member_name' => $member->member_name,
                    'grade' => $member->grade,
                    'company_name' => $companyName,
                    'company_phone' => $companyPhone,
                    'business_license' => serialize($uploadPic),
                    'apply_grade' => $apply_grade,
                    'created_at' => time(),
                ]);
            } else {
                $errorData = array('message' => '您还有未审核的申请，请耐心等待', 'url' => asset('personal/index'));
                return view('errors.error', compact('errorData'));
            }
        } else if ($member->grade == $apply_grade) {
            $errorData = array('message' => '无申请权限', 'url' => asset('personal/index'));
            return view('errors.error', compact('errorData'));
        } else {
            $errorData = array('message' => '用户不存在', 'url' => asset('personal/index'));
            return view('errors.error', compact('errorData'));
        }
        return $this->myJoininList();
    }

    public function uploadImg()
    {
        if (empty($_FILES)) return Api::responseMessage(5000, '', '上传图片不存在');

        $images = isset($_FILES['uploadPic']) ? $_FILES['uploadPic'] : '';
        //上传图片

        $rootPath = config('upload')['rootPath'];
        $path = '/apply/' . date('Y-m-d') . '/' . rand(0, 100);
        $imgPath = $rootPath . $path;
        if (!file_exists($imgPath)) {
            mkdir($imgPath, 0777, true);
        }
        $ext = strtolower(pathinfo($images['name'], PATHINFO_EXTENSION));

        $file_name = $this->getUniName() . '.' . $ext;
        $imgPath = $imgPath . '/' . $file_name;

        //这里调用的比例压缩图片大小的函数
        if (@move_uploaded_file($images['tmp_name'], $imgPath)) {
            $result['file_name'] = $this->img_domain . $path . '/' . $file_name;
        }else{
            $result['message'] = '图片上传失败';
        }
        exit(json_encode($result));
    }

    public function delImg(Request $request)
    {
        $data_img = $request->input('data_img');
        if (empty($data_img)) return Api::responseMessage(5000, '', '图片不存在');

        $pos = stripos($data_img, $this->img_domain);
        $data_img = substr($data_img, ($pos) + strlen($this->img_domain));

        $file_path = config('upload')['rootPath'] . $data_img;

        @unlink($file_path);

        return Api::responseMessage(0);
    }

    /**
     * 产生唯一字符串(文件名)
     * @return string
     */
    private function getUniName()
    {
        return md5(uniqid(microtime(true), true));
    }


   /**
    * 用户申请采购
    *  用户是否处于申请审核状态，若处于申请状态审核状态，则显示审核情况，否者显示申请填写页面
       $again 表示是不是从审核失败页面中的点击重新提交资料按钮进来的
    */
    public function manage_procurement($again = 0){

        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }
        //判断用户是否有申请记录
        $memberJoinin = MemberJoinIn::where('member_id', $member_id)
                        ->first();
        if($memberJoinin && !$again){
            return view("member.my_member_manage_procurement_result", compact('memberJoinin'));
        }else{
            //若不存在，则进行申请

            // 省地址数组（新建地址信息需要）
            $province_dct = DctArea::select('id', 'name', 'pid')
                ->where('pid', 0)
                ->where('is_use', 1)
                ->get()
                ->toArray();

            return view('member.my_member_manage_procurement', compact('province_dct'));

        }


    }

    /**
     *申请信息详情
     */
    public function manage_procurement_detail(){
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }
        //判断用户是否有申请记录
        $memberJoinin = MemberJoinIn::where('member_id', $member_id)
            ->first();

        //上传照片反序列化
        $pic_arr = unserialize($memberJoinin->business_license);

        return view("member.my_member_manage_procurement_msg", compact('memberJoinin','pic_arr'));
    }


   /**
    * 提交申请信息
    */
    public function submitAdd(Request $request){

        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        //根据提交类别：企业用户还是个人用户进行不同的处理
        $applyType = $request->input('applyType');
        if($applyType == 1){ //企业用户类型提交资料

            //获取用户申请采购要达到的用户级别
            $apply_grade = $this->get_apply_grade('apply_grade_company');
            if(!$apply_grade){ //说明后台还没有设置该变量的值
                $errorData = array('message' => '目前系统还不支持申请采购功能', 'url' => asset('personal/index'));
                return view('errors.error', compact('errorData'));
            }

            $joinin_style = $request->input('applyType');
            $uploadPic = $request->input('com_uploadPic');
            $contacts_name = $request->input('contacts_name');
            $contacts_mobile = $request->input('contacts_mobile');
            $company_name = $request->input('company_name');
            $company_phone = $request->input('company_phone');

            $area_info = $request->input('area_info');
            $address = $request->input('address');

            $uploadPic = explode(',', $uploadPic);

            $member = Member::find($member_id);
            if ($member && $member->grade < $apply_grade ) { //小于团采用户才可申请
                $joinin = MemberJoinIn::where('member_id', $member_id)
                    ->where('verify_state', 1)
                    ->first();
                if (!$joinin) {

                    //提交前把所有以前提交过的全部删除，数据库只保存最新提交的一条
                    DB::table('member_joinin')->where('member_id',$member_id)->delete();

                    MemberJoinIn::create([
                        'member_id' => $member_id,
                        'member_name' => $member->member_name,
                        'grade' => $member->grade,

                        'joinin_style' => $joinin_style,

                        'contacts_name' => $contacts_name,
                        'contacts_mobile' => $contacts_mobile,

                        'company_name' => $company_name,
                        'company_phone' => $company_phone,

                        'area_info' => $area_info,
                        'address' => $address,

                        'business_license' => serialize($uploadPic),

                        'apply_grade' => $apply_grade,
                        'created_at' => time(),
                    ]);

                    return redirect('personal/index');
                } else {
                    $errorData = array('message' => '您还有未审核的申请，请耐心等待', 'url' => asset('personal/index'));
                    return view('errors.error', compact('errorData'));
                }
            } else if ($member->grade >= $apply_grade) {
                $errorData = array('message' => '无申请权限', 'url' => asset('personal/index'));
                return view('errors.error', compact('errorData'));
            } else {
                $errorData = array('message' => '用户不存在', 'url' => asset('personal/index'));
                return view('errors.error', compact('errorData'));
            }

        }else{ //个人用户类别提交资料

            //获取用户申请采购要达到的用户级别
            $apply_grade = $this->get_apply_grade('apply_grade_personal');
            if(!$apply_grade){ //说明后台还没有设置该变量的值
                $errorData = array('message' => '目前系统还不支持申请采购功能', 'url' => asset('personal/index'));
                return view('errors.error', compact('errorData'));
            }

            $joinin_style = $request->input('applyType');
            $uploadPic = $request->input('per_uploadPic');
            $contacts_name = $request->input('contacts_name');
            $contacts_mobile = $request->input('contacts_mobile');
            $email = $request->input('email');

            $uploadPic = explode(',', $uploadPic);

            $member = Member::find($member_id);
            if ($member && $member->grade < $apply_grade ) { //小于团采用户才可申请
                $joinin = MemberJoinIn::where('member_id', $member_id)
                    ->where('verify_state', 1)
                    ->first();
                if (!$joinin) {

                    //提交前把所有以前提交过的全部删除，数据库只保存最新提交的一条
                    DB::table('member_joinin')->where('member_id',$member_id)->delete();

                    MemberJoinIn::create([
                        'member_id' => $member_id,
                        'member_name' => $member->member_name,
                        'grade' => $member->grade,

                        'joinin_style' => $joinin_style,

                        'contacts_name' => $contacts_name,
                        'contacts_mobile' => $contacts_mobile,
                        'email' => $email,

                        'business_license' => serialize($uploadPic),

                        'apply_grade' => $apply_grade,
                        'created_at' => time(),
                    ]);

                    return redirect('personal/index');
                } else {
                    $errorData = array('message' => '您还有未审核的申请，请耐心等待', 'url' => asset('personal/index'));
                    return view('errors.error', compact('errorData'));
                }
            } else if ($member->grade >= $apply_grade) {
                $errorData = array('message' => '您的当前用户级别大于申请级别，无申请权限', 'url' => asset('personal/index'));
                return view('errors.error', compact('errorData'));
            } else {
                $errorData = array('message' => '用户不存在', 'url' => asset('personal/index'));
                return view('errors.error', compact('errorData'));
            }

        }


    }


    /**
     * 我要供货
     */
    public function manage_supply(){
        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        // 判断用户是否有处于待审核状态的申请记录
        $supply_info = MemberSupply::where('member_id', $member_id)
            ->first();
        if($supply_info){
            //上传照片反序列化
            $pic_arr = unserialize($supply_info->business_license);
            return view("member.my_member_manage_supply_msg", compact('supply_info','pic_arr'));
        }else{
            // 若不存在，则进行申请

            // 省地址数组（新建地址信息需要）
            $province_dct = DctArea::select('id', 'name', 'pid')
                ->where('pid', 0)
                ->where('is_use', 1)
                ->get()
                ->toArray();

            return view('member.my_member_manage_supply', compact('province_dct'));

        }

    }

//
//    /**
//     * 供货详细信息
//     */
//    public function manage_supply_detail(){
//        // 当前登录用户信息
//        $member = Auth::user();
//        if ($member) {
//            $member_id = $member->member_id;
//        } else {
//            // 当前用户没有登录
//            return redirect('/oauth');
//        }
//        //判断用户是否有申请记录
//        $supply_info = MemberSupply::where('member_id', $member_id)
//            ->first();
//
//        //上传照片反序列化
//        $pic_arr = unserialize($supply_info->business_license);
//
//        return view("member.my_member_manage_supply_msg", compact('supply_info','pic_arr'));
//
//    }


    /**
     * 我要供货提交资料
     */

    public function manage_supply_add(Request $request){

        // 当前登录用户信息
        $member = Auth::user();
        if ($member) {
            $member_id = $member->member_id;
        } else {
            // 当前用户没有登录
            return redirect('/oauth');
        }

        $uploadPic = $request->input('uploadPic');
        $contacts_name = $request->input('contacts_name');
        $contacts_mobile = $request->input('contacts_mobile');
        $company_name = $request->input('company_name');
        $company_phone = $request->input('company_phone');
        $email = $request->input('email');

        $area_info = $request->input('area_info');
        $address = $request->input('address');
        $management_scope = $request->input('management_scope');

        $uploadPic = explode(',', $uploadPic);

        $supply = MemberSupply::where('member_id', $member_id)
                 ->first();
        if (!$supply) {
            MemberSupply::create([
                'member_id' => $member_id,
                'member_name' => $member->member_name,
                'grade' => $member->grade,

                'contacts_name' => $contacts_name,
                'contacts_mobile' => $contacts_mobile,

                'company_name' => $company_name,
                'company_phone' => $company_phone,
                'email' => $email,

                'management_scope' => $management_scope,

                'area_info' => $area_info,
                'address' => $address,

                'business_license' => serialize($uploadPic),

                'created_at' => time(),
            ]);

            return redirect('personal/index');
        }else{
            //已经申请过了
            $errorData = array('message' => '您已提交过资料', 'url' => asset('personal/index'));
            return view('errors.error', compact('errorData'));

        }

    }


    /**
     * 邀请好友
     */
   public function invite_friend(){

       // 当前登录用户信息
       $member = Auth::user();
       if ($member) {
           $member_id = $member->member_id;
           $nick_name = $member->nick_name;
       } else {
           // 当前用户没有登录
           return redirect('/oauth');
       }

       //微信jsapi
       $signPackage = session('signPackage');

       $url = "http://$_SERVER[HTTP_HOST]";
       $share_link = $url . "/member/toGetReward/".$member_id;

       //获取该用户邀请别人的总数以及获得的奖励信息
       $reward_log = DB::table('invite_friend_reward_log')
                        ->where('is_inviter',1)
                        ->where('member_id',$member_id)
                        ->get();

       $card_balance_total = 0; //得到卡余额的总数
       $wallet_total = 0;  //得到零钱总额
       $yesb_total = 0; //得到虚拟币的总数
       $people_num = 0; //邀请的总人数
       $membercard_total = 0; //得到会员的总数

       foreach($reward_log as $k => $v){
           switch($v->reward_code){

               case 'card_balance':

                   $card_balance_total += $v->reward_num;
                   break;

               case 'wallet':

                   $wallet_total += $v->reward_num;
                   break;

               case 'yesb':

                   $yesb_total += $v->reward_num;
                   break;
               case 'member_card':

                   $membercard_total+= $v->reward_num;
                   break;
           }

           $people_num++;
       }



       return view('member.my_member_inviteFriend', compact('member_id','nick_name','signPackage','share_link','card_balance_total','wallet_total','yesb_total','people_num','membercard_total'));

   }

    /**
     * 用户点击邀请者分享的链接，进入领奖品页面
     * $member_id 分享者id
     */
    public function toGetReward($member_id){

        $member = Member::find($member_id);

        //查询接受邀请者得到的奖品信息
        $reward_id = $this->get_invite_friend_reward('invited_reward');

        $share_info = DB::table('invite_friend_reward')->where('id',$reward_id)->first();

        return view('member.my_member_inviteFriend_get', compact('member_id','share_info','member'));

    }

}
