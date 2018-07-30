<?php

namespace App\controllers;

use App\Http\Controllers\Controller;
use App\models\cjk\CjkCrowdFunding;
use App\models\cjk\CjkCrowdFundingValue;
use Illuminate\Http\Request;

/**
 * 众筹
 * @author      :lishuo
 * Class        :UserController
 * @package     :App\controllers\wx
 */
class CrowdFundingController extends BaseController
{
    public function index()
    {
        $crowdFunding = CjkCrowdFunding::where('state',0)->orderBy('sort')->get();  //只显示众筹中的
        foreach ($crowdFunding as $item) {
            $values = CjkCrowdFundingValue::where('zid', $item->id)->get();
            $num = 0;
            foreach ($values as $value) {
                $num += $value->money;
            }
            if ($num == 0) {
                $item['percent'] = 0;
            } else {
                $percent = $num / $item->total * 100;
                $item['percent'] =  number_format($percent);
            }
            if ($item['percent'] > 100) {
                $item['percent'] = 100;
            }
            $time_start = time();
            $item['day'] = intval(($item->time_expire - $time_start ) / 86400) + 1;

        }
        return view('/raise/index', compact('crowdFunding'));
    }

    public function details($id)
    {
        $crowdFunding = CjkCrowdFunding::find($id);
        $values = CjkCrowdFundingValue::where('zid', $crowdFunding->id)->get();
        $num = 0;
        foreach ($values as $value) {
            $num += $value->money;
        }
        $crowdFunding['num'] = $num;
        if ($num == 0) {
            $crowdFunding['percent'] = 0;
        } else {
            $percent = $num / $crowdFunding->total * 100;
            $crowdFunding['percent'] =  number_format($percent);
        }
        $time_start = time();
        $crowdFunding['day'] = intval(($crowdFunding->time_expire - $time_start ) / 86400) + 1;

        return view('/raise/detail',compact('crowdFunding'));
    }

    /**
     * 添加用户信息
     */
    public function add(Request $request)
    {
        try{
            $zid =  $request->input('zid');
//            $crowd_funding = CjkCrowdFunding::where('id',$zid)->first();
//            $time = time();
//            if($time>$crowd_funding->time_expire){  //当前时间大于结束时间
//                
//            }else{
//                
//            }
            $money = CjkCrowdFunding::where('id',$zid)->value('minimum');
            $money = $request->input('num')*$money;

            $data = [
                'zid' =>$zid,
                'name' => $request->input('name'),
                'mobile' => $request->input('mobile'),
                'money' => $money,
            ];
            CjkCrowdFundingValue::create($data);
        }catch(\Exception $e){
            return 'error';
        }
        $result = $this->result($zid);
        return $result;


        //暂时不许要，后续修改 $zid = $request->input('id');
//        $user = CjkCrowdFundingValue::where('id',$zid)->where('mobile',$request->input('mobile'))->first();
//        if(!$user){    //不存在
//            CjkCrowdFundingValue::create($data);
//        }else{//存在
//            $crowdUser = CjkCrowdFundingValue::where('id',$zid)->where('mobile',$request->input('mobile'))->first();
//            
//            $crowdUser->update(['money'=>$crowdUser->money+ $request->input('money')]);
//            
//        }
        //手机号存在就叠加，不存在酒新增
    }
    
    
    public function result($id)
    {
        $crowdFunding = CjkCrowdFunding::find($id);
        $values = CjkCrowdFundingValue::where('zid', $crowdFunding->id)->get();
        $num = 0;
        foreach ($values as $value) {
            $num += $value->money;
        }
        $crowdFunding['num'] = $num;
        if ($num == 0) {
            $crowdFunding['percent'] = 0;
        } else {
            $percent = $num / $crowdFunding->total * 100;
            $crowdFunding['percent'] =  number_format($percent);
        }
        $crowdFunding['day'] = intval(($crowdFunding->time_expire - $crowdFunding->time_start ) / 86400) + 1;

        $crowdFunding['is_add'] = 1;
        
        return $crowdFunding;
    }
}