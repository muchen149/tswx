<?php

namespace App\lib;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 网易考拉接口
 *
 */
class WyKlThirdApiTwo
{
    /**
     * 接口地址
     *
     * @var string
     */
    protected static $baseUrl;

    /**
     * 方法
     *
     * @var string
     */
    protected static $method;

    /**
     * 应用key
     *
     * @var string
     */
    protected static $appKey;

    /**
     * 应用secret
     *
     * @var string
     */
    protected static $appSecret;

    /**
     * 时间戳
     *
     * @var string
     */
    protected static $timestamp;

    /**
     * 渠道id
     *
     * @var string
     */
    protected static $channelid;

    /**
     * api版本
     *
     * @var string
     */
    protected static $v;

    /**
     * 初始化
     */
    protected static function init($method)
    {
        self::$appKey = config('thirdapi')['wykl_two']['appKey'];
        self::$appSecret = config('thirdapi')['wykl_two']['appSecret'];
        self::$baseUrl = config('thirdapi')['wykl_two']['testUrl']; //测试环境
        //self::$baseUrl = config('thirdapi')['wykl']['apiUrls']; //正式环境
        self::$method = config('thirdapi')['wykl_two']['method'][$method];
        self::$channelid = config('thirdapi')['wykl_two']['channelId'];
        self::$v = config('thirdapi')['wykl_two']['v'];
        self::$timestamp = Carbon::now()->format('Y-m-d H:i:s');
    }

    /**
     * 2018年7月23日18:13:33
     * 签名
     * @param $secret
     * @param $params
     * @return string
     */
    static public function getSignSort($secret, $params) {
        ksort($params);
        $result = "";
        if (count($params)) {
            foreach ($params as $key => $param) {
                $result .= $key . $param;
            }
        }
        return strtoupper(md5($secret . $result . $secret));
    }
    /**
     * 2018年7月23日18:11:23
     * 所有商品SkuID查询
     * author yu
     * @return array
     */

    static public function skuIds()
    {
        self::init('skuIds');
        $app_key = self::$appKey;
        $app_secret = self::$appSecret;
        $channel_id = self::$channelid;
        $timestamp = self::$timestamp;
        $header = [];
        $params = [
            'app_key' => $app_key,
            'channelId' => $channel_id,
            'sign_method' => 'md5',
            'timestamp' => $timestamp,
            'v' => '1.0',
        ];

        //调用下面的方法按要求生成签名
        $params['sign'] = self::getSignSort($app_secret, $params);
        $stockList = self::getCurl($header, $params);

        Log::notice("param:".serialize($params));
        Log::notice("result:".serialize($stockList));

        return $stockList;
    }

    /**
     * 2018年7月23日18:44:22
     * 商品详情批量查询(sku详情)
     * author yu
     * @param $skuids
     * @return array
     */
    static public function skuItems($skuids)
    {
        self::init('skuItems');
        $app_key = self::$appKey;
        $app_secret = self::$appSecret;
        $channel_id = self::$channelid;
        $timestamp = self::$timestamp;
        $header = [];
        $params = [
            'app_key' => $app_key,
            'channelId' => $channel_id,
            'sign_method' => 'md5',
            'timestamp' => $timestamp,
            'v' => '1.0',
            'skuIds' => $skuids,
            'queryType' =>0,

        ];

        //调用下面的方法按要求生成签名
        $params['sign'] = self::getSignSort($app_secret, $params);
        $stockList = self::getCurl($header, $params);
        return $stockList;
    }

    /**
     * 2018年7月2日14:09:43
     * @author yu
     * 订单确认
     * @param $data
     * @return array
     */
    static public function orderConfirm($data)
    {
        self::init('orderConfirm');
        $app_key = self::$appKey;
        $app_secret = self::$appSecret;
        $channel_id = self::$channelid;
        $timestamp = self::$timestamp;
        $header = [];

        $thirdPartOrderId=$data['thirdPartOrderId'];

        $orderItemList['orderItemList']=$data['orderItemList'];
        $userInfo['userInfo']=$data['userInfo'];

        $itemL=\GuzzleHttp\json_encode($orderItemList);
        $user=\GuzzleHttp\json_encode($userInfo,JSON_UNESCAPED_UNICODE);

        $params = [
            'source' => $channel_id,
            'timestamp' => $timestamp,
            'v' => '1.0',
            'sign_method' => 'md5',
            'app_key' => $app_key,
            'orderItemList'=>$itemL,
            'userInfo'=>$user,
        ];

        //调用下面的方法按要求生成签名
        $params['sign'] = self::getSignSort($app_secret, $params);
        $result = self::getCurl($header, $params);
        Log::notice("param:".serialize($params));
        Log::notice("result:".serialize($result));

        $logarr=array(
            'plat_order_sn'=>$thirdPartOrderId,
            'orderconfirm_req'=>serialize($params),
            'orderconfirm_res'=>serialize($result),

        );
        DB::table('wykl_iflog')->insert($logarr);
        if ($result['httpcode'] == 200) {
            if ($result['content']['recCode'] == 200) {
                $orderForm = $result['content']['orderForm'];
                $packageList=$orderForm['packageList'];

                //self::createOrder($data,$packageList); //单独下单
                self::bookpayorder($data,$packageList); //下单且支付

            }
        }

        return $result;
    }

    /**
     * 2018年7月2日14:10:25
     * 订单支付
     * @author yu
     * @param $data
     * @param $packageList
     * @return array
     */

    static public function bookpayorder($data,$packageList)
    {
        self::init('bookpayorder');
        $app_key = self::$appKey;
        $app_secret = self::$appSecret;
        $channel_id = self::$channelid;
        $timestamp = self::$timestamp;
        $header = [];

        $userInfo['userInfo']=$data['userInfo'];
        $thirdPartOrderId =$data['thirdPartOrderId'];

        $u=\GuzzleHttp\json_encode($userInfo,JSON_UNESCAPED_UNICODE);
        $orderSkus=[];
        $rtnRst=[];
        foreach($packageList as $package){
            $goodsList=$package['goodsList'];
            unset($orderSkus);
            foreach ($goodsList as $good) {
                $sku = [
                    'goodsId'=>$good['goodsId'],
                    'skuId' => $good['skuId'],
                    'buyAmount' => $good['goodsBuyNumber'],
                    'channelSalePrice' => $good['goodsPayAmount'],
                    'warehouseId'=>$good['warehouseId'],
                ];
                $orderSkus[] = $sku;
            }
            $orderItemList['orderItemList']=$orderSkus;
            $l=\GuzzleHttp\json_encode($orderItemList,JSON_UNESCAPED_UNICODE);
            $params = [
                'source' => $channel_id,
                'timestamp' => $timestamp,
                'v' => '1.0',
                'sign_method' => 'md5',
                'app_key' => $app_key,
                'thirdPartOrderId'=>$thirdPartOrderId,
                'orderItemList'=>$l,
                'userInfo'=>$u,
            ];

            //调用下面的方法按要求生成签名
            $params['sign'] = self::getSignSort($app_secret, $params);
            $result = self::getCurl($header, $params);

            Log::notice("param:".serialize($params));
            Log::notice("result:".serialize($result));
            Log::notice('--------------------------------');
            $rtnRst[]=$result;
        }
        return $rtnRst;
    }

    /**
     * 2018年7月23日21:35:07
     * 第三方订单查询
     * author yu
     * @param $orderId
     * @return array
     */
    static public function queryOrderStatus($orderId)
    {
        self::init('queryOrderStatus');
        $app_key = self::$appKey;
        $app_secret = self::$appSecret;
        $channel_id = self::$channelid;
        $timestamp = self::$timestamp;
        $header = [];

        $thirdPartOrderId=$orderId;

        $params = [
            'channelId' => $channel_id,
            'timestamp' => $timestamp,
            'v' => '1.0',
            'sign_method' => 'md5',
            'app_key' => $app_key,
            'thirdPartOrderId'=>$thirdPartOrderId,
        ];

        //调用下面的方法按要求生成签名
        $params['sign'] = self::getSignSort($app_secret, $params);
        $result = self::getCurl($header, $params);

        Log::notice("param:".serialize($params));
        Log::notice("result:".serialize($result));

        return $result;
    }

    /**
     * 2018年7月23日21:41:44
     * 取消第三方订单
     * @param $orderId
     * @param int $reasonId 1 收货人信息有误;2 商品数量或款式需调整;3 有更优惠的购买方案;4 考拉一直未发货;5 商品缺货;6 我不想买了;7 其他原因
     * @return array
     */
    static public function cancelOrder($orderId,$reasonId=6)
    {
        self::init('cancelOrder');
        $app_key = self::$appKey;
        $app_secret = self::$appSecret;
        $channel_id = self::$channelid;
        $timestamp = self::$timestamp;
        $header = [];
        $thirdPartOrderId=$orderId;
        $params = [
            'timestamp' => $timestamp,
            'v' => '1.0',
            'sign_method' => 'md5',
            'app_key' => $app_key,
            'thirdpartOrderId'=>$thirdPartOrderId,
            'reasonId'=>$reasonId,
            'channelId' => $channel_id,
        ];

        //调用下面的方法按要求生成签名
        $params['sign'] = self::getSignSort($app_secret, $params);
        $result = self::getCurl($header, $params);

        Log::notice("param:".serialize($params));
        Log::notice("result:".serialize($result));

        return $result;
    }



    /**
     * 2018-6-12 16:21:42
     * 根据goodsId(前台)查询出下面所有的skuId
     * @param $spuIds
     * @return array
     */
    static public function querySkuIdsByGoodsIds($spuIds)
    {
        self::init('querySkuIdsByGoodsIds');
        $app_key = self::$appKey;
        $app_secret = self::$appSecret;
        $channel_id = self::$channelid;
        $timestamp = self::$timestamp;
        $header = [];
        $params = [
            'app_key' => $app_key,
            'channelId' => $channel_id,
            'sign_method' => 'md5',
            'timestamp' => $timestamp,
            'v' => '1.0',
            'goodsIds' => $spuIds,
            'queryType' =>0,
        ];
        //调用下面的方法按要求生成签名
        $params['sign'] = self::getSignSort($app_secret, $params);
        $spuIdList = self::getCurl($header, $params);
        return $spuIdList;
    }

    /**
     * 请求第三方
     * @param $header
     * @param $params
     * @return array
     */
    static public function  getCurl($header, $params) {
        $params = http_build_query((array)$params);
        $ch = curl_init();
        $url=self::$baseUrl.self::$method;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Meilishuo Snake Connect');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $response = curl_exec($ch);
        $res = array();
        $res['httpcode'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res['content'] = json_decode($response, TRUE);
        return $res;
    }
}