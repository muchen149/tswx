<?php

namespace App\controllers\ys;

use App\facades\Api;
use App\models\dct\DctArea;
use App\models\member\MemberAddress;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberAddressController extends BaseController
{
    /**
     * 添加一个地址
     * @auth 杨瑞
     * @param Request $request
     * @return array
     */
    public function save(Request $request)
    {
        // 买家id
        $member_id = Auth::user()->member_id;

        // 详细的区域地址,不做验证
        $address = $request->input('address');

        // 收货人
        $recipient_name = $request->input('name');
        if (empty($recipient_name)) {
            return Api::responseMessage(50002, '', '收货人姓名不能为空');
        }

        // 电话
        $mobile = $request->input('mobile');
        if (empty($mobile)) {
            return Api::responseMessage(50002, '', '收货人手机号不能为空');
        }

        // 省id 区id  市id
        $province_id = $request->input('province_id');
        $area_id = $request->input('area_id');
        $city_id = $request->input('city_id');
        if (empty($province_id) || empty($area_id ||
            empty($city_id) || empty($address_info))) {
            return Api::responseMessage(50002, '', '地址不能为空');
        }

        // 省name 市name 区name
        $province_name = DctArea::where('id', $province_id)->where('is_use', 1)->value('name');
        $area_name = DctArea::where('id', $area_id)->where('is_use', 1)->value('name');
        $city_name = DctArea::where('id', $city_id)->where('is_use', 1)->value('name');
        if (empty($province_name) || empty($city_name) || empty($area_name)) {
            return Api::responseMessage(50002, '', '无效的区域地址');
        }

        // 存在id 则是新增地址   不存在 则是编辑地址
        $id = $request->input('id');
        if (empty($id)) {

            // 修改默认地址为新增的地址
            MemberAddress::where('member_id', $member_id)
                ->where('is_default', 1)
                ->update(['is_default' => 0]);

            // 增加一条新地址并设为默认地址
            $address_obj = MemberAddress::create([
                'member_id' => $member_id,
                'recipient_name' => $recipient_name,
                'province_id' => $province_id,
                'area_id' => $area_id,
                'city_id' => $city_id,
                'area_info' => $province_name . $area_name . $city_name ,
                'address' => $address,
                'mobile' => $mobile,
                'is_default' => 1,
                'use_state' => 0,
            ]);

            if (!$address_obj->exists) {
                return Api::responseMessage(50002, '', '新增地址失败');
            }
        } else {

            $ref_num = MemberAddress::where('address_id', $id)->update(array(
                'member_id' => $member_id,
                'recipient_name' => $recipient_name,
                'province_id' => $province_id,
                'area_id' => $area_id,
                'city_id' => $city_id,
                'area_info' => $province_name . $area_name . $city_name,
                'address' => $address,
                'mobile' => $mobile,
            ));

            if ($ref_num == 0) {
                return Api::responseMessage(50002, '', '编辑地址失败');
            }

            $address_obj = MemberAddress::where('address_id', $id)->first();
        }

        return Api::responseMessage(0, $address_obj->toArray());
    }

    /** ajax
     * 通过地址列表id删除一条地址记录,逻辑上删除，不能物理删除，因为当物理删除后，订单详情中会找不到该地址了
     * @param $address_id
     * @return mixed
     */
    public function delete($address_id)
    {
        $address_id = (int)$address_id;
        if ($address_id <= 0)
        {
            return Api::responseMessage(50002, null, '地址ID格式不对');
        }

        $address = MemberAddress::where('address_id', $address_id)->first();
        if (!$address) {
            return Api::responseMessage(50002, null, '地址ID不存在');
        }

        // 删除当前地址并设置默认地址
//        $address->delete();
        MemberAddress::where('address_id', $address_id)
            ->update(['use_state' => -1]);

        // 如果现有地址没有默认地址，设置一个默认地址
        // is_default   是否默认【0:否;1:是;】
        // use_state    有效状态【0:有效;1:无效;-1:已删除】
        $obj_user = Auth::user();
        if ($obj_user)
        {
            $member_id = $obj_user->member_id;
            $default_address = MemberAddress::where('member_id',$member_id)
                ->where('use_state', 0)
                ->where('is_default', 1)
                ->first();
            if (!$default_address){
                $default_address = MemberAddress::select('address_id')
                    ->where('member_id',$member_id)
                    ->where('use_state', 0)
                    ->orderBy('address_id', 'asc')
                    ->first();
                if ($default_address){
                    $address_id = $default_address->address_id;
                    MemberAddress::where('address_id', $address_id)
                        ->update(['is_default' => 1]);
                }
            }
        }

        return Api::responseMessage(0);
    }

    /**
     * 设置默认的收货地址
     * @auth 杨瑞
     * @param $address_id
     * @return mixed
     */
    public function setDefault($address_id)
    {
        MemberAddress::where('member_id', Auth::user()->member_id)
            ->where('is_default', 1)
            ->update(['is_default' => 0]);
        return MemberAddress::where('address_id', $address_id)
            ->update(['is_default' => 1]);
    }


    /** ajax
     * 根据父id获取子地址信息
     * @param $id
     * @return mixed
     */
    public function getChild($id)
    {
        if ((int)$id <= 0) {
            return Api::responseMessage(10000);
        }

        $areas = DctArea::selectZd()
            ->where('pid', $id)
            ->where('is_use', 1)
            ->get()
            ->toArray();

        return Api::responseMessage(0, $areas);
    }

    /**
     * jiang
     * 地址管理中用户的地址列表
     */

    public function getAddressList(){

        // ---------------------------------------1、买家收货人地址----------------------------------------
        //判断当前用户是否登陆过
        $member = Auth::user();
        //用户没登录，去登陆
        if(!$member){
            return redirect('/oauth');
        }
        // 当前买家收货地址列表
        $address_info = MemberAddress::selectZd()
            ->where('member_id', $member->member_id)
            ->where('use_state', 0)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        return view('address.address_list', compact('address_info'));
    }


    /**
     * 地址管理中地址的编辑
     */
    public function addressEdit($address_id=null){
            // 省地址数组（新建地址信息需要）
            $province_dct = DctArea::select('id', 'name', 'pid')
                ->where('pid', 0)
                ->where('is_use', 1)
                ->get()
                ->toArray();

            $address_info = MemberAddress::selectZd()
                ->where('member_id', Auth::user()->member_id)
                ->where('address_id', $address_id)
                ->get()
                ->toArray();

            return view('address.address_edit', compact('address_info','province_dct'));
    }

    /**
     * 地址管理中地址的添加
     */
    public function addressAdd(){

        // 省地址数组（新建地址信息需要）
        $province_dct = DctArea::select('id', 'name', 'pid')
            ->where('pid', 0)
            ->where('is_use', 1)
            ->get()
            ->toArray();

        return view('address.address_add',compact('province_dct'));
    }


    /**
     * jiang
     * 地址管理中地址添加保存
     */
    public function addressSave(Request $request){
        // 买家id
        $member_id = Auth::user()->member_id;

        // 详细的区域地址,不做验证
        $address = $request->input('address');

        // 收货人
        $recipient_name = $request->input('name');
        if (empty($recipient_name)) {
            return Api::responseMessage(50002, '', '收货人姓名不能为空');
        }

        // 电话
        $mobile = $request->input('mobile');
        if (empty($mobile)) {
            return Api::responseMessage(50002, '', '收货人手机号不能为空');
        }

        // 省id 区id  市id
        $province_id = $request->input('province_id');
        $area_id = $request->input('area_id');
        $city_id = $request->input('city_id');
        if (empty($province_id) || empty($area_id ||
                empty($city_id) || empty($address_info))) {
            return Api::responseMessage(50002, '', '地址不能为空');
        }

        // 省name 市name 区name
        $province_name = DctArea::where('id', $province_id)->where('is_use', 1)->value('name');
        $area_name = DctArea::where('id', $area_id)->where('is_use', 1)->value('name');
        $city_name = DctArea::where('id', $city_id)->where('is_use', 1)->value('name');
        if (empty($province_name) || empty($city_name) || empty($area_name)) {
            return Api::responseMessage(50002, '', '无效的区域地址');
        }

        $is_default = $request->input('is_default');

        // 不存在id 则是新增地址   存在 则是编辑地址
        $id = $request->input('id');
        if (empty($id)) {
            //若新添加的地址设置为默认地址
            if($is_default == 1){
                // 修改默认地址为新增的地址
                MemberAddress::where('member_id', $member_id)
                    ->where('is_default', 1)
                    ->update(['is_default' => 0]);

                // 增加一条新地址并设为默认地址
                $address_obj = MemberAddress::create([
                    'member_id' => $member_id,
                    'recipient_name' => $recipient_name,
                    'province_id' => $province_id,
                    'area_id' => $area_id,
                    'city_id' => $city_id,
                    'area_info' => $province_name . $area_name . $city_name,
                    'address' => $address,
                    'mobile' => $mobile,
                    'is_default' => 1,
                    'use_state' => 0,
                ]);
            }else{
                // 增加一条新地址并设为默认地址
                $address_obj = MemberAddress::create([
                    'member_id' => $member_id,
                    'recipient_name' => $recipient_name,
                    'province_id' => $province_id,
                    'area_id' => $area_id,
                    'city_id' => $city_id,
                    'area_info' => $province_name . $area_name . $city_name,
                    'address' => $address,
                    'mobile' => $mobile,
                    'is_default' => 0,
                    'use_state' => 0,
                ]);
            }

            if (!$address_obj->exists) {
                return Api::responseMessage(50002, '', '新增地址失败');
            }

        }else{
           //编辑地址
            if($is_default == 1){
                MemberAddress::where('member_id', $member_id)
                    ->where('is_default', 1)
                    ->update(['is_default' => 0]);

                $ref_num = MemberAddress::where('address_id', $id)->update(array(
                    'member_id' => $member_id,
                    'recipient_name' => $recipient_name,
                    'province_id' => $province_id,
                    'area_id' => $area_id,
                    'city_id' => $city_id,
                    'area_info' => $province_name . $city_name. $area_name ,
                    'address' => $address,
                    'is_default' => 1,
                    'mobile' => $mobile,
                ));

            }else{
                $ref_num = MemberAddress::where('address_id', $id)->update(array(
                    'member_id' => $member_id,
                    'recipient_name' => $recipient_name,
                    'province_id' => $province_id,
                    'area_id' => $area_id,
                    'city_id' => $city_id,
                    'area_info' => $province_name . $area_name . $city_name,
                    'address' => $address,
                    'is_default' => 0,
                    'mobile' => $mobile,
                ));
            }

            if ($ref_num == 0) {
                return Api::responseMessage(50002, '', '编辑地址失败');
            }

        }


        return Api::responseMessage(0);

    }




}
