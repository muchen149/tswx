<?php

namespace App\lib;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * 网易严选接口
 *
 */
class WyYxThirdApi
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

    static public function register($methods)
    {
        self::init('register');
        //$response = self::getHttpClient()->post(self::$baseUrl . self::$method, [
        $response = self::getHttpClient()->post(self::$baseUrl , [
            'query' => [
                'method' => self::$method, //20170626增加method 参数
                'appKey' => self::$appKey,
                'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp,[
                    'methods' => $methods,
                ]),
                'timestamp' => self::$timestamp,
                'methods' => $methods,
            ],
        ]);
        $prarm=[
            'method' => self::$method, //20170626增加method 参数
            'appKey' => self::$appKey,
            'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, []),
            'timestamp' => self::$timestamp,
            'methods' => $methods,
        ];
        Log::notice('-------register---------');
        Log::alert($prarm);
        Log::alert($response->getBody());
        return json_decode($response->getBody(), true);
    }

    /**
     * 初始化
     */
    protected static function init($method)
    {
        self::$appKey = config('thirdapi')['wyyx']['appKey'];
        self::$appSecret = config('thirdapi')['wyyx']['appSecret'];
        self::$baseUrl = config('thirdapi')['wyyx']['testUrl']; //测试环境
        //self::$baseUrl = config('thirdapi')['wyyx']['apiUrls']; //正式环境
        self::$method = config('thirdapi')['wyyx']['method'][$method];
        self::$timestamp = Carbon::now()->format('Y-m-d H:i:s');
    }

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
    | 商品列表查询接口
    |--------------------------------------------------------------------------
    */

    static public function getSign($method,$appKey, $appSecret, $timestamp, $paramMap)
    {
        //将请求参数按名称排序
        $treeMap = [
            'method' =>$method,
            'appKey' => $appKey,
            'timestamp' => $timestamp,
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

    static public function list_method()
    {
        self::init('list');
        //$response = self::getHttpClient()->post(self::$baseUrl . self::$method, [
        $response = self::getHttpClient()->post(self::$baseUrl , [
            'query' => [
                'method' => self::$method, //20170626增加method 参数
                'appKey' => self::$appKey,
                'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, []),
                'timestamp' => self::$timestamp,
            ],
        ]);
        $prarm=[
            'method' => self::$method, //20170626增加method 参数
            'appKey' => self::$appKey,
            'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, []),
            'timestamp' => self::$timestamp,
        ];
        Log::notice('-------list---------');
        Log::alert($prarm);
        Log::alert($response->getBody());
        return json_decode($response->getBody(), true);
    }

    /*
    |--------------------------------------------------------------------------
    | 商品信息查询接口
    |--------------------------------------------------------------------------
    */

    static public function skuIds()
    {
        self::init('skuIds');
        //$response = self::getHttpClient()->post(self::$baseUrl . self::$method, [
        $response = self::getHttpClient()->post(self::$baseUrl , [
            'query' => [
                'method' => self::$method, //20170626增加method 参数
                'appKey' => self::$appKey,
                'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, []),
                'timestamp' => self::$timestamp,
            ],
        ]);
        $prarm=[
            'method' => self::$method, //20170626增加method 参数
            'appKey' => self::$appKey,
            'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, []),
            'timestamp' => self::$timestamp,
        ];
        Log::notice('-------skuids---------');
        Log::alert($prarm);
        return json_decode($response->getBody(), true);
    }

    /*
    |--------------------------------------------------------------------------
    | 下单
    |--------------------------------------------------------------------------
    */

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

    static public function skuItems($itemIds)
    {
        self::init('skuItems');
        //$response = self::getHttpClient()->post(self::$baseUrl . self::$method, [
        $response = self::getHttpClient()->post(self::$baseUrl , [
            'query' => [
                'method' => self::$method, //20170626增加method 参数
                'appKey' => self::$appKey,
                'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, [
                    'itemIds' => $itemIds,
                ]),
                'timestamp' => self::$timestamp,
                'itemIds' => $itemIds,
            ],
        ]);
        Log::notice('--------skuitems ---');
        Log::alert([
            'method' => self::$method, //20170626增加method 参数
            'appKey' => self::$appKey,
            'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, [
                'itemIds' => $itemIds,
            ]),
            'timestamp' => self::$timestamp,
            'itemIds' => $itemIds,
        ]);
        return json_decode($response->getBody(), true);
    }

    /*
    |--------------------------------------------------------------------------
    | 订单确认收货
    |--------------------------------------------------------------------------
    */

    static public function createOrder($order)
    {
        self::init('createOrder');
        $order=\GuzzleHttp\json_encode($order);
        //$response = self::getHttpClient()->post(self::$baseUrl . self::$method, [
        $response = self::getHttpClient()->post(self::$baseUrl , [
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
        //dd($response);
        return json_decode($response->getBody(), true);
    }

    /*
    |--------------------------------------------------------------------------
    | 订单查询
    |--------------------------------------------------------------------------
    */

    static public function cancelOrder($orderId)
    {
        self::init('cancelOrder');
        //$response = self::getHttpClient()->post(self::$baseUrl . self::$method, [
        $response = self::getHttpClient()->post(self::$baseUrl , [
            'query' => [
                'method' => self::$method, //20170626增加method 参数
                'appKey' => self::$appKey,
                'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, [
                    'orderId' => $orderId
                ]),
                'timestamp' => self::$timestamp,
                'orderId' => $orderId,
            ],
        ]);
        Log::notice('--------cancel order ---');
        Log::alert([
            'method' => self::$method, //20170626增加method 参数
            'appKey' => self::$appKey,
            'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, [
                'orderId' => $orderId
            ]),
            'timestamp' => self::$timestamp,
            'orderId' => $orderId,
        ]);
        return json_decode($response->getBody(), true);
    }

    /*
    |--------------------------------------------------------------------------
    | 渠道库存查询
    |--------------------------------------------------------------------------
    */

    static public function confirmOrder($orderId, $packageId, $confirmTime)
    {
        self::init('confirmOrder');
        //$response = self::getHttpClient()->post(self::$baseUrl . self::$method, [
        $response = self::getHttpClient()->post(self::$baseUrl , [
            'query' => [
                'method' => self::$method, //20170626增加method 参数
                'appKey' => self::$appKey,
                'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, [
                    'orderId' => $orderId,
                    'packageId' => $packageId,
                    'confirmTime' => $confirmTime
                ]),
                'timestamp' => self::$timestamp,
                'orderId' => $orderId,
                'packageId' => $packageId,
                'confirmTime' => $confirmTime
            ],
        ]);

        Log::notice('--------confirm order ---');
        Log::alert([
            'method' => self::$method, //20170626增加method 参数
            'appKey' => self::$appKey,
            'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, [
                'orderId' => $orderId,
                'packageId' => $packageId,
                'confirmTime' => $confirmTime
            ]),
            'timestamp' => self::$timestamp,
            'orderId' => $orderId,
            'packageId' => $packageId,
            'confirmTime' => $confirmTime
        ]);
        return json_decode($response->getBody(), true);
    }

    /*
    |--------------------------------------------------------------------------
    | 获得签名
    |--------------------------------------------------------------------------
    */

    static public function getOrder($orderId)
    {
        self::init('getOrder');
        //$response = self::getHttpClient()->post(self::$baseUrl . self::$method, [
        $response = self::getHttpClient()->post(self::$baseUrl , [
            'query' => [
                'method' => self::$method, //20170626增加method 参数
                'appKey' => self::$appKey,
                'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, [
                    'orderId' => $orderId
                ]),
                'timestamp' => self::$timestamp,
                'orderId' => $orderId
            ],
        ]);

        Log::notice('--------get order ---');
        Log::alert([
            'method' => self::$method, //20170626增加method 参数
            'appKey' => self::$appKey,
            'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, [
                'orderId' => $orderId
            ]),
            'timestamp' => self::$timestamp,
            'orderId' => $orderId
        ]);

        return json_decode($response->getBody(), true);
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
            'sign' => self::getSign(self::$method,self::$appKey, self::$appSecret, self::$timestamp, [
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