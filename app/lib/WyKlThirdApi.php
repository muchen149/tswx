<?php

namespace App\lib;

use App\models\order\Order;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 网易考拉接口
 *
 */
class WyKlThirdApi
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

    static public function spuSkuIds()
    {
        self::init('spuSkuIds');
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
        dd($stockList);

    }

    /*
    |--------------------------------------------------------------------------
    | 商品列表查询接口
    |--------------------------------------------------------------------------
    */

    /**
     * 初始化
     */
    protected static function init($method)
    {
        self::$appKey = config('thirdapi')['wykl']['appKey'];
        self::$appSecret = config('thirdapi')['wykl']['appSecret'];
        self::$baseUrl = config('thirdapi')['wykl']['testUrl']; //测试环境
        //self::$baseUrl = config('thirdapi')['wykl']['apiUrls']; //正式环境
        self::$method = config('thirdapi')['wykl']['method'][$method];
        self::$channelid = config('thirdapi')['wykl']['channelId'];
        self::$v = config('thirdapi')['wykl']['v'];
        self::$timestamp = Carbon::now()->format('Y-m-d H:i:s');
    }

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

    /*
    |--------------------------------------------------------------------------
    | 商品信息查询接口
    |--------------------------------------------------------------------------
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

    static public function skuIdsTest()
    {
        self::init('skuIds');

        $response = self::getHttpClient()->post(self::$baseUrl , [
            'query' => [
                'method' => self::$method,
                'appKey' => self::$appKey,
                'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, []),
                'timestamp' => self::$timestamp,
            ],
        ]);
        return json_decode($response->getBody(), true);
    }

    /*
    |--------------------------------------------------------------------------
    | 订单取消申请
    |--------------------------------------------------------------------------
    */

    /**
     * Get a fresh instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    protected static function getHttpClient()
    {
        return new Client();
    }

    /*
    |--------------------------------------------------------------------------
    | 订单确认收货
    |--------------------------------------------------------------------------
    */

    static public function getSign($method,$appKey, $appSecret, $timestamp,$v, $paramMap)
    {
        //将请求参数按名称排序
        $treeMap = [
            'timestamp' => $timestamp,
            'v' => $v,
            'app_key' => $appKey,
            'sign_method' =>'md5',
        ];
        if (null != $paramMap && is_array($paramMap)) {
            $treeMap = array_merge($treeMap, $paramMap);
        }
        ksort($treeMap);

        //遍历treeMap，将参数值进行拼接
        $keyvalue='';
        foreach($treeMap as $key=>$value)
        {
           $keyvalue = $keyvalue .$key."=".$value;
        }
        //$treeValues = array_values($treeMap);
        //$str = '';
        //$str = self::getArrayValues($treeValues, $str);


        //参数值拼接的字符串收尾添加appSecret值
        $waitSignStr = $appSecret . $keyvalue . $appSecret;
        Log::alert($waitSignStr);
        //获取MD5加密后的字符串
        $sign = strtoupper(md5($waitSignStr));
        //Log::alert($sign);
        /*dd([
            'timestamp' => $timestamp,
            'sign' => $sign,
        ]);*/
        return $sign;
    }


    /*
        |--------------------------------------------------------------------------
        | 商品信息增量查询
        |--------------------------------------------------------------------------
        */

    static public function queryChangedGoodsInfo($startTime,$endTime)
    {
        self::init('queryChangedGoodsInfo');
        $app_key = self::$appKey;
        $app_secret = self::$appSecret;
        $channel_id = self::$channelid;
        $timestamp = self::$timestamp;
        $header = [];
        $params = [
            'source' => $channel_id,
            'startTime'=>20171215,//$startTime,
            'endTime'=>20180115,//$endTime,
            'timestamp' => $timestamp,
            'v' => '1.0',
            'sign_method' => 'md5',
            'app_key' => $app_key,
        ];

        //调用下面的方法按要求生成签名
        $params['sign'] = self::getSignSort($app_secret, $params);
        $result = self::getCurl($header, $params);


        Log::notice("param:".serialize($params));
        Log::notice("result:".serialize($result));
        return $result;
    }
    /*
   |--------------------------------------------------------------------------
   | 单个商品信息查询
   |--------------------------------------------------------------------------
   */

    static public function skuItem($skuid,$queryType=0)
    {
        self::init('skuItem');
        $app_key = self::$appKey;
        $app_secret = self::$appSecret;
        $channel_id = self::$channelid;
        $timestamp = self::$timestamp;
        $header = [];
        $params = [
            'channelId' => $channel_id,
            'timestamp' => $timestamp,
            'v' => '1.0',
            'sign_method' => 'md5',
            'app_key' => $app_key,
            'skuId' => $skuid,
            'queryType' =>$queryType,
            //'channelSalePrice'=>0

        ];

        //调用下面的方法按要求生成签名
        $params['sign'] = self::getSignSort($app_secret, $params);
        $result = self::getCurl($header, $params);

        Log::notice("param:".serialize($params));
        Log::notice("result:".serialize($result));
        return $result;
    }

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
    /*
    |--------------------------------------------------------------------------
    | 渠道库存查询
    |--------------------------------------------------------------------------
    */

    static public function createOrder($data,$packageList)
    {
        self::init('createOrder');
        $app_key = self::$appKey;
        $app_secret = self::$appSecret;
        $channel_id = self::$channelid;
        $timestamp = self::$timestamp;
        $header = [];

        //$orderItemList['orderItemList']=$data['orderItemList'];
        $userInfo['userInfo']=$data['userInfo'];
        $thirdPartOrderId =$data['thirdPartOrderId'];

        $u=\GuzzleHttp\json_encode($userInfo,JSON_UNESCAPED_UNICODE);
        //dd($u);
        $orderSkus=[];
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
                'thirdPartOrderId'=>$data['thirdPartOrderId'],
                'orderItemList'=>$l,
                'userInfo'=>$u,
            ];

            //调用下面的方法按要求生成签名
            $params['sign'] = self::getSignSort($app_secret, $params);
            $result = self::getCurl($header, $params);

            Log::notice("param:".serialize($params));
            Log::notice("result:".serialize($result));

            return $result;


        }





        /*self::init('createOrder');
        $order=\GuzzleHttp\json_encode($order);*/
        /*$plat_order_sn='';
        $orderT=Order::where('plat_order_sn',$plat_order_sn)->first();*/

        //$thirdPartOrderId =$order->plat_order_sn;
/*
        $orderItemList=[];
        $orderItem['goodsId']=;
        $orderItem['skuId']=;
        $orderItem['buyAmount']=;
        $orderItem['channelSalePrice']=;
        $orderItem['warehouseId']=;



        $userInfo['accountId']=;
        $userInfo['name']=;
        $userInfo['mobile']=;
        $userInfo['email']=;
        $userInfo['provinceName']=;
        $userInfo['provinceCode']=;
        $userInfo['cityName']=;
        $userInfo['cityCode']=;
        $userInfo['districtName']=;
        $userInfo['districtCode']=;
        $userInfo['address']=;
        $userInfo['postCode']=;
        $userInfo['phoneNum']=;
        $userInfo['phoneAreaNum']=;
        $userInfo['phoneExtNum']=;
        $userInfo['identityId']=;
        $userInfo['identityPicFront']=;
        $userInfo['identityPicBack']=;*/





        //$response = self::getHttpClient()->post(self::$baseUrl . self::$method, [
/*        $response = self::getHttpClient()->post(self::$baseUrl , [
            'query' => [
                'method' => self::$method, //20170626增加method 参数
                'appKey' => self::$appKey,
                'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, [
                    'order' => $order
                ]),
                'timestamp' => self::$timestamp,
                'order' => $order
            ],
        ]);
        Log::notice('--------createOrder ---');
        Log::alert([
            'method' => self::$method, //20170626增加method 参数
            'appKey' => self::$appKey,
            'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, [
                'order' => $order
            ]),
            'timestamp' => self::$timestamp,
            'order' => $order
        ]);
        return json_decode($response->getBody(), true);*/
    }

    static public function cancelOrder($orderId,$reasonId=6)
    {
        /*
        //reasonId 枚举如下
        1 收货人信息有误
        2 商品数量或款式需调整
        3 有更优惠的购买方案
        4 考拉一直未发货
        5 商品缺货
        6 我不想买了
        7 其他原因
        */
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
            //'remark'=>'',
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
                $orderRst=self::bookpayorder($data,$packageList); //下单且支付


            }
        }

        return $result;
    }

    /**
     * 2018年7月2日14:10:25
     * @author yu
     * 订单支付
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

    /*
   |--------------------------------------------------------------------------
   | 订单支付
   |--------------------------------------------------------------------------
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

    //下单且支付

    static public function payOrder($orderId)
    {
        self::init('payOrder');
        $app_key = self::$appKey;
        $app_secret = self::$appSecret;
        $channel_id = self::$channelid;
        $timestamp = self::$timestamp;
        $header = [];

        $thirdPartOrderId=$orderId;

        $params = [
            'source' => $channel_id,
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
    /*
   |--------------------------------------------------------------------------
   | 订单关闭
   |--------------------------------------------------------------------------
   */

    static public function closeOrder($orderId,$reasonId=6)
    {
        /*
        //reasonId 枚举如下
        1 收货人信息有误
        2 商品数量或款式需调整
        3 有更优惠的购买方案
        4 考拉一直未发货
        5 商品缺货
        6 我不想买了
        7 其他原因
        */
        self::init('closeOrder');
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


    static public function stockInfo($skuIds)
    {
        self::init('stockInfo');
        Log::alert($skuIds);
        //$response = self::getHttpClient()->post(self::$baseUrl . self::$method, [
        $response = self::getHttpClient()->post(self::$baseUrl , [
            'query' => [
                'method' => self::$method, //20170626增加method 参数
                'appKey' => self::$appKey,
                'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, [
                    'skuIds' => "$skuIds"
                ]),
                'timestamp' => self::$timestamp,
                'skuIds' => "$skuIds"
            ],
        ]);
        $prarm=[
            'method' => self::$method, //20170626增加method 参数
            'appKey' => self::$appKey,
            'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret,self::$v, self::$timestamp, [
                'skuIds' => "$skuIds"
            ]),
            'timestamp' => self::$timestamp,
            'skuIds' => "$skuIds"
        ];
        Log::notice('-------stockinfo param---------');
        Log::alert($prarm);

        return json_decode($response->getBody(), true);
    }

    /**
     * post请求
     * @param $url
     * @param $data
     * @return mixed
     */
    protected static function postData($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $tmpInfo;
    }

    /**
     * 递归将数组所有的values拼接
     *
     */
    private static function getArrayValues($arr, $s)
    {
        foreach ($arr as $k => &$v) {
            //判断value是否是数组，如果是数组 递归数组value拼接
            if (is_array($v)) {
                $s = self::getArrayValues($v, $s);
                //重新赋值保证传入的数据顺序不改变
                $arr[$k] = $s;
            }
        }
        //判断是否是一维数组
        if(count($arr) == count($arr, 1)){
            $s = join('', array_values($arr));
        }
        return $s;
    }

    /**
     * get请求
     * @param $url
     * @return mixed
     */
    public function getData($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $info = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl);
        }

        curl_close($curl);
        return $info;
    }
}