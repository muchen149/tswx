<?php

namespace App\controllers\ys;

use App\Http\Controllers\Controller;
use App\facades\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransportTemplateController extends BaseController
{
    /**
     * 根据商品数量及收货地点区县ID、运费模板ID或商品spu_id(sku_id)，获取运费金额
     * @param   object $request post 过来的信息
     * @return  @json               运费金额及其它信息
     */
    public function goodsTransportCost(Request $request)
    {
        $code = 0;
        $message = "获取运费";
        $data = null;

        //  如果指定了运费模板，直接应用；没有指定，从spu_id 关联的维护模板获取
        $tpl_transport_id = (int)($request->input('tpl_transport_id'));
        if ($tpl_transport_id == 0) {
            // 商品 Spu_id，没有指定，退出
            $spu_id = (int)($request->input('spu_id'));
            if ($spu_id == 0) {
                $sku_id = (int)($request->input('sku_id'));
                if ($sku_id > 0) {
                    $spu_id = (int)(DB::table('goods_sku')->where('sku_id', $sku_id)->value('spu_id'));
                }
            }

            if ($spu_id > 0) {
                // ->toSql()
                $tpl_transport = DB::table('goods_spu')
                    ->join('tpl_transport',
                        function ($join) {
                            $join->on('goods_spu.tpl_transport_id', '=', 'tpl_transport.id')
                                ->where('tpl_transport.state', '=', 0);
                        })
                    ->where('goods_spu.spu_id', $spu_id)
                    ->select('goods_spu.spu_id', 'goods_spu.freight', 'goods_spu.tpl_transport_id')
                    ->first();

                if ($tpl_transport) {
                    $freight_cost = $tpl_transport->freight;
                    if ($freight_cost > 0.00) {
                        return Api::responseMessage($code, $freight_cost, $message);
                    } else {
                        $tpl_transport_id = $tpl_transport->tpl_transport_id;
                    }
                } else {
                    // 没有关联运费模板，直接返回 0 运费
                    return Api::responseMessage($code, '0.00', $message);
                }
            } else {
                $code = 1;
                $message = '商品spu_id 或 sku_id 不存在';
                return Api::responseMessage($code, $data, $message);
            }
        }

        // 默认 1 件
        $goods_number = (int)($request->input('goods_number'));
        if ($goods_number == 0) {
            $goods_number = 1;
        }

        $sendee_city_id = (int)($request->input('sendee_city_id'));
        if ($sendee_city_id == 0) {
            // 默认当前用户所在城市ID
            $member = Auth::user();     // 买家信息 ->first()
            $sendee_city_id = DB::table('member_extend')
                ->where('member_id', $member)
                ->value('city_id');
            $sendee_city_id = (int)$sendee_city_id;
        }

        try {
            $data = $this->getTransportCost($tpl_transport_id, $goods_number, $sendee_city_id);
        } catch (PDOException $e) {
            $code = 1;
            $data = 0.00;
            $message = $e->getMessage();
        }

        return Api::responseMessage($code, $data, $message);
    }


    /**
     * 根据运费模板ID、商品数量、到达城市ID，获取运费金额
     * @param $tpl_transport_id (运费模板ID)
     * @param $goods_number (商品数量)
     * @param $sendee_city_id (收件人所在区县ID，合法性由外部验证)
     * @return $transport_cost(运费金额，货币型)
     */
    public function getTransportCost($tpl_transport_id, $goods_number = 1, $sendee_city_id = 0,$tpl_gPrice=0)
    {
        // 1、运费模板停用或不存在，直接退出
        $transport_cost = 0.00;
        $goods_number = (int)$goods_number;
        $is_exist_tpl = DB::table('tpl_transport')
            ->where('id', $tpl_transport_id)
            ->where('state', 0)->first();
            //->count();
        if (empty($is_exist_tpl)) {
            return $transport_cost;
        }else{
            if($is_exist_tpl->isFreeDelivery==1){
                if($tpl_gPrice>=$is_exist_tpl->limitNum){
                    return $transport_cost;
                }
            }else if($is_exist_tpl->isFreeDelivery==2){
                if($goods_number>=$is_exist_tpl->limitNum){
                    return $transport_cost;
                }
            }
        }

        if ($goods_number <= 0) {
            $goods_number = 1;
        }

        // 获取区县（城市）所属的地区【地级市】
        $area_id = 0;
        $sendee_city_id = (int)$sendee_city_id;
        if ($sendee_city_id > 0) {
            $obj_area = DB::table('dct_area')
                ->select('pid')
                ->where('id', $sendee_city_id)
                ->first();
            if ($obj_area) {
                $area_id = $obj_area->pid;
            }
        }


        // 1、首先按照指定地区计价方式算运费
        if ($area_id > 0 || $sendee_city_id > 0) {
            $str_area_id = '%,' . (string)$area_id . ',%';
            $str_city_id = '%,' . (string)$sendee_city_id . ',%';
            $tpl_transportDetail = DB::table('tpl_transport_detail')
                ->select('id', 'top_area_id', 'area_id',
                    'first_number', 'first_price', 'next_number', 'next_price')
                ->where('tpl_id', $tpl_transport_id)
                ->where(function ($query) use ($str_area_id, $str_city_id) {
                    $query->Where('area_id', 'like', $str_area_id)
                        ->orWhere('area_id', 'like', $str_city_id);
                })
                ->first();
            $transport_cost = $this->calculateTransportCost($tpl_transportDetail, $goods_number);
        }

        // 2、如果没有指定地区，启用默认计价方式计算运费
        if (bccomp($transport_cost, 0.00) == 0) {
            $tpl_transportDetail = DB::table('tpl_transport_detail')
                ->select('id', 'top_area_id', 'area_id',
                    'first_number', 'first_price', 'next_number', 'next_price')
                ->where('tpl_id', $tpl_transport_id)
                ->where('is_default', 1)
                ->first();
            $transport_cost = $this->calculateTransportCost($tpl_transportDetail, $goods_number);
        }

        return $transport_cost;
    }


    /**
     * 根据运费计价模板、商品数量等，计算运费
     * @param $tpl_transportDetail (运费计价模板)
     * @param $goods_number (商品数量)
     * @return $transport_cost(运费金额，货币型)
     */
    private function calculateTransportCost($tpl_transportDetail = null, $goods_number = 1)
    {
        // sprintf('%.2f',0),输出字符串：'0.00'
        // number_format(0,2),输出字符串：'0.00'
        $cost = 0.00;       // 运费合计
        $base_cost = 0.00;  // 基础运费
        $add_cost = 0.00;   // 增件费用

        if ($tpl_transportDetail) {
            // 首件数量
            $first_number = $tpl_transportDetail->first_number;
            if ($first_number <= 0) {
                // 首件小于等于零，认定为固定费用
                $first_number = 100000;
            }

            // 首件费用、续件数量、续件
            $first_price = $tpl_transportDetail->first_price;
            $next_number = $tpl_transportDetail->next_number;
            $next_price = $tpl_transportDetail->next_price;

            // 1、计算基础运费，形如：5件内
            $base_cost = $first_price;
            $add_number = ($goods_number > $first_number ? ($goods_number - $first_number) : 0);

            // 2、计算续件运费，形如：每3件2元
            if ($add_number > 0 && $next_number > 0) {
                // 相除并向上取整
                $add_count = ceil($add_number / $next_number);

                // 两个小数相乘，保留 2 位小数
                $add_cost = bcmul($next_price, $add_count, 2);
            }
        }

        // 两个小数相加，保留 2 两位小数
        $cost = bcadd($base_cost, $add_cost, 2);
        return $cost;
    }
}