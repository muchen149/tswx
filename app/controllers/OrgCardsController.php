<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/11
 * Time: 10:04
 */

namespace App\controllers;


use App\facades\Api;
use App\controllers\OrderController;
use App\controllers\wx\WxPayController;
use App\models\goods\GoodsClass;
use App\models\goods\GoodsSpu;
use App\models\goods\GoodsSpuImages;
use App\models\goods\GoodsSpecDefine;
use App\models\goods\GoodsSpecValueDct;
use App\models\dct\DctArea;
use App\models\member\Member;
use App\models\orgcards\MemberOrgCards;
use App\models\orgcards\OrgcardForm;
use App\models\orgcards\OrgCards;
use App\models\orgcards\OrgFlag;
use App\models\orgcards\OrgformData;
use App\models\orgcards\OrgnazitionInfo;
use EasyWeChat\Foundation\Application;
use App\models\order\Order as PlatOrder;

use App\models\goods\GoodsSku;
use App\models\goods\GoodsSkuImages;
use App\models\member\MemberCollect;
use App\models\order\Order;
use App\models\member\MemberAddress;


use App\models\member\MemberCart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class OrgCardsController extends BaseController
{

    public function cardList(){

        $member_id=0;
        if (Auth::user()) {
            $member = Auth::user();
            $member_id = $member->member_id;
        } else {
            redirect('/oauth');
        }

        $cardList =array();
        $memberOrgcards=MemberOrgCards::where('member_id',$member_id)->get();
        foreach($memberOrgcards as $mCards) {
            $orgcard = OrgCards::where('id', $mCards->orgcard_id)->where('state', 1)->first();
            if ($orgcard) {
                $orgFlags = OrgFlag::where('orgid', $mCards->orgid)->get();
                $orgcard->orgFlags = $orgFlags;
                array_push($cardList, $orgcard);
            }
        }

        //获取卡列表
        //$cardList =

        $subscribe = $this->getSubscribe($member_id);
        return view('orgcards.orgCardsList', compact('cardList','subscribe','member'));
    }

    public function showOrgcard($orgid){
        if(!$orgid){

        }else {
            $member_id=0;
            if (Auth::user()) {
                $member = Auth::user();
                $member_id = $member->member_id;
            } else {
                redirect('/oauth');
            }
            $hasFlag=1;
            $orgInfo = OrgnazitionInfo::where('id', $orgid)->first();
            $orgCard = OrgCards::where('orgid',$orgid)->where('state', 1)->first();
            if($orgCard) {
                $orgCard->card_image = $this->getFullPictureUrl($orgCard->card_image);
                $orgFlags = OrgFlag::where('orgid', $orgid)->where('is_use', 1)->get();
                if (!$orgFlags->count()) {
                    $hasFlag = 0;
                }

                $form = OrgcardForm::where('fid', $orgCard->fid)->first();
                if($form){
                    $form->fmsg = $this->MooHtmlspecialchars($form->fmsg);
                }
                /*if (!$share_info) {
                    $errorData = array('code' => 50002, 'message' => '该礼品已失效');
                    return view("errors.error", compact('errorData'));
                }*/

                //微信jsapi
                $signPackage = session('signPackage');

                $subscribe = $this->getSubscribe($member_id);
                return view('orgcards.showOrgCard', compact(
                    'orgInfo', 'orgCard', 'orgFlags', 'form', 'hasFlag','subscribe','signPackage'
                ));
            }else{
                $errorData=[
                    'message'=>'机构卡不存在或已过期，谢谢关注！',
                ];
                return view('errors.error',compact('errorData'));
            }
        }

    }

    /**
     * 将特殊字符转成 HTML 格式。比如<a href='test'>Test</a>转换为&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;
     * @param $value - 字符串或者数组变量
     * @return array
     */
    public function MooHtmlspecialchars($value) {
        return is_array($value) ? array_map('MooHtmlspecialchars', $value) : preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1', str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'),array('&', '"', '<', '>'),$value));
    }

    public function showSubOrgcard($orgid,$suborgid){

        if(!$orgid||!$suborgid){

        }else {
            $member_id = 0;
            if (Auth::user()) {
                $member = Auth::user();
                $member_id = $member->member_id;
            } else {
                redirect('/oauth');
            }
            $hasFlag = 1;
            $orgInfo = OrgnazitionInfo::where('id', $orgid)->first();
            $orgCard = OrgCards::where('orgid', $orgid)->where('state', 1)->first();
            if ($orgCard) {
                $orgCard->card_image = $this->getFullPictureUrl($orgCard->card_image);
                $orgFlags = OrgFlag::where('orgid', $orgid)->where('is_use', 1)->get();
                if (!$orgFlags->count()) {
                    $hasFlag = 0;
                }

                //微信jsapi
                $signPackage = session('signPackage');

                /* $subscribe = $this->getSubscribe($member_id);
                 return view('orgcards.showOrgCard', compact(
                     'orgInfo', 'orgCard', 'orgFlags', 'form', 'hasFlag','subscribe','signPackage'
                 ));*/
                $flag_id = $suborgid;
                $orgcard_id = $orgCard->id;

                $member_orgcard = MemberOrgCards::where('member_id', $member_id)->where('orgcard_id', $orgcard_id)->first();
                if ($member_orgcard) {
                    //return view('orgcards.orgCardsGetFailed', compact('orgcard_id'));
                    $rurl='/orgcards/orgcardInfo/'.$orgcard_id;
                    return redirect($rurl);
                } else {

                        try {
                            DB::beginTransaction();

                            $umember = Member::where('member_id', $member_id)->first();
                            $u_data = [
                                'member_id' => $member_id,
                                'orgid' => $orgid,
                                'orgcard_id' => $orgcard_id,
                                'org_flag' => $flag_id,

                            ];
                            $member_orgcards = MemberOrgCards::create($u_data);
                            //$umember->update($u_data);
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error('更新机构卡信息失败  控制器:OrgCardsController@add');
                            return Api::responseMessage(1, null, '更新机构卡信息出错! ');
                        }
                    $url='/orgcards/orgcardInfo/'.$orgcard_id;
                    return redirect($url);
                }
            } else{
                $errorData = [
                    'message' => '机构卡不存在或已过期，谢谢关注！',
                ];
                return view('errors.error', compact('errorData'));
            }
        }

    }

    public function orgcardInfo($card_id){

        $member_id=0;
        if (Auth::user()) {
            $member = Auth::user();
            $member_id = $member->member_id;
        } else {
            redirect('/oauth');
        }
        $orgcard_id=$card_id;
        $hasFlag=1;
        if($orgcard_id) {
            $orgCard = OrgCards::where('id', $orgcard_id)->first();
            $orgCard->card_image = $this->getFullPictureUrl($orgCard->card_image);

            $orgFlags = OrgFlag::where('orgid', $orgCard->orgid)->where('is_use', 1)->get();
            if (!$orgFlags->count()) {
                $hasFlag = 0;
            }

            $form = OrgcardForm::where('fid', $orgCard->fid)->first();
            if($form){
                $form->fmsg = $this->MooHtmlspecialchars($form->fmsg);
            }
            /*if (!$share_info) {
                $errorData = array('code' => 50002, 'message' => '该礼品已失效');
                return view("errors.error", compact('errorData'));
            }*/
            $memberOrgCard=MemberOrgCards::where('member_id',$member_id)->where('orgcard_id',$orgcard_id)->first();
            $flagName='未选择';
            if($memberOrgCard->org_flag!=0){
                foreach($orgFlags as $vflag){
                    if($memberOrgCard->org_flag==$vflag->id){
                        $flagName=$vflag->name;
                        break;
                    }
                }
            }

            $subscribe = $this->getSubscribe($member_id);
            return view('orgcards.orgCardInfo', compact(
                'orgCard', 'member','memberOrgCard','subscribe','orgFlags','hasFlag','flagName'
            ));
        }

    }

    public function orgcardGoods($card_id){

        $member_id=0;
        $grade = 10;
        if (Auth::user()) {
            $member = Auth::user();
            $member_id = $member->member_id;
            $grade = $member->grade;
        } else {
            redirect('/oauth');
        }
        $orgcard_id=$card_id;//$member->orgcard_id;
        if($orgcard_id) {
            $orgCard = OrgCards::where('id', $orgcard_id)->first();
            $orgCard->card_image = $this->getFullPictureUrl($orgCard->card_image);

            $form = OrgcardForm::where('fid', $orgCard->fid)->first();
            if($form){
                $form->fmsg = $this->MooHtmlspecialchars($form->fmsg);
            }
            /*if (!$share_info) {
                $errorData = array('code' => 50002, 'message' => '该礼品已失效');
                return view("errors.error", compact('errorData'));
            }*/

            $orgInfo = OrgnazitionInfo::where('id', $orgCard->orgid)->first();
            //遍历，得到各个spu信息
            $tem = array();
            $tem['share_gifts_label_id'] = 1;
            $tem['share_gifts_label_name'] = 'zhuanshu';
            $tem['g_list'] = array();
            $label_goods_list = array();
            $goods_list = unserialize($orgInfo->goods_list);
            foreach($goods_list as $key => $good){
                    $g_info = array();
                    //取出每个商品spu的规格
                    $goods = GoodsSpu::select('spu_id', 'spu_code',
                        'gc_id', 'gb_name', 'keywords',
                        'spu_name', 'ad_link_url', 'spu_attr',
                        'spec_name', 'spec_value',
                        'spu_market_price', 'spu_plat_price',
                        'spu_groupbuy_price', 'spu_trade_price',
                        'spu_partner_price', 'spu_points_limit', 'is_virtual',
                        'main_image', 'mobile_content', 'state')
                        ->where("spu_id", $good['spu_id'])
                        ->first();
                    // SPU 不存在，直接退出
                    if (!$goods) {
                        continue;
                    }

                    $g_info['spu_id'] = $goods->spu_id;
                    $g_info['spu_name'] = $good['spu_name']; //商品名称和图片读取序列化中的
                    $g_info['main_image'] = $this->getFullPictureUrl($good['main_image']);
                    $g_info['spu_plat_price'] = $goods->spu_plat_price;
                    $g_info['spu_market_price'] = $goods->spu_market_price;
                    $g_info['spu_partner_price'] = $goods->spu_partner_price;
                    $g_info['spu_trade_price'] = $goods->spu_trade_price;
                    $g_info['spu_groupbuy_price'] = $goods->spu_groupbuy_price;

                    //$g_info['spu_price'] = $goods->spu_plat_price;//20170911-根据会员等级取得相应价格信息
                    $g_info['spu_price'] = $this->getSpuPrice($grade, $goods);


                    $goods->spec_value = (unserialize($goods->spec_value) == false ?
                        [] : unserialize($goods->spec_value));
                    $spuSpec = [];
                    foreach ($goods->spec_value as $g_key => $item) {
                        // $item 为规格值数组，$g_key 为spec_id；
                        $spec = GoodsSpecDefine::find($g_key);
                        $data = [
                            'data_type' => $spec->data_type,
                            'spec_name' => $spec->name,
                            'spec_value' => []
                        ];

                        foreach ($item as $key1 => $value) {
                            $data['spec_value'][$key1] = $value;
                        }

                        // $data 信息项：规格名、规格值（数组）、规格值类型
                        array_push($spuSpec, $data);
                    }
                    $g_info['spuSpec'] = $spuSpec;

                    array_push($tem['g_list'], $g_info);
                }

            array_push($label_goods_list, $tem);

            return view('orgcards.orgcardGoods_index', compact(
                'orgCard', 'member','label_goods_list'
            ));
        }

    }

    public function add(Request $request)
    {
        $member_id=0;
        if (Auth::user()) {
            $member = Auth::user();
            $member_id = $member->member_id;
        } else {
            redirect('/oauth');
        }

        $fid     = $request->input('FRMID');

        if(!$fid) $fid=0;

        $flag_id = $request->input('org_flag');
        $orgcard_id = $request->input('card_id');
        $orgid = $request->input('orgid');

        $formdata = OrgformData::where('member_id',$member_id)->where('fid',$fid)->get(['member_id','fid'])->toarray();

        $member_orgcard=MemberOrgCards::where('member_id',$member_id)->where('orgcard_id',$orgcard_id)->first();
        if($member_orgcard){
            return view('orgcards.orgCardsGetFailed',compact('orgcard_id'));
        }else {
            $get_flag = 1;
            if (!empty($user_id)) {
                $get_flag = 0;
            } else {
                $content = serialize($_POST);
                $data = [
                    'fid' => $fid,
                    'content' => empty($content) ? '' : $content,
                    'member_id' => $member_id,
                    'addtime' => time(),
                ];
                try {
                    DB::beginTransaction();
                    $formdata = OrgformData::create($data);
                    $umember = Member::where('member_id', $member_id)->first();
                    $u_data = [
                        'member_id' => $member_id,
                        'orgid' => $orgid,
                        'orgcard_id' => $orgcard_id,
                        'org_flag' => $flag_id,

                    ];
                    $member_orgcards = MemberOrgCards::create($u_data);
                    //$umember->update($u_data);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('更新机构卡信息失败  控制器:OrgCardsController@add');
                    return Api::responseMessage(1, null, '更新机构卡信息出错! ');
                }

            }
        }

        return view('orgcards.orgCardsResult',compact('formdata','fid','get_flag','orgcard_id'));
        /*return redirect()->action('FormController@index',[$fid]);*/


    }

    public function updateFlag(Request $request)
    {
        $member_id=0;
        if (Auth::user()) {
            $member = Auth::user();
            $member_id = $member->member_id;
        } else {
            redirect('/oauth');
        }


        $flag_id = $request->input('org_flag');
        $orgcard_id = $request->input('card_id');
        $orgid = $request->input('orgid');


        $member_orgcard=MemberOrgCards::where('member_id',$member_id)->where('orgcard_id',$orgcard_id)->first();
        if($member_orgcard){
            $data=array(
                'org_flag'=>$flag_id,
            );
            $member_orgcard->update($data);
           // return view('orgcards.orgCardsGetFailed',compact('orgcard_id'));
            $uri='/orgcards/orgcardInfo/'.$orgcard_id;
            return redirect($uri);
        }else {

        }

    }
    /**
     * 为变量或者数组添加转义
     * @param string $value - 字符串或者数组变量
     * @return array
     */
    public function MooAddslashes($value) {
        return $value = is_array($value) ? array_map('MooAddslashes', $value) : addslashes($value);
    }


}