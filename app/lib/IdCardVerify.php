<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4 0004
 * Time: 下午 4:36
 */
namespace App\lib;

use Illuminate\Support\Facades\Log;

/**
 * 2018年7月4日16:38:06
 * 身份证验证接口
 * @author yu
 */
class IdCardVerify
{
    /**
     * 身份证地址
     * @var string
     */
    protected static $url;

    /**
     * 身份证图片地址
     * @var string
     */
    protected static $img_url;
    /**
     * 身份证实名认证--应用key
     * @var string
     */
    protected static $idKey;

    /**
     * 身份证OCR识别--应用key
     * @var string
     */
    protected static $imgKey;


    protected static function init()
    {
        self::$url = config('yydapi')['IdCard']['url'];
        self::$img_url = config('yydapi')['IdCard']['img_url'];
        self::$idKey = config('yydapi')['IdCard']['idKey'];
        self::$imgKey = config('yydapi')['IdCard']['imgKey'];
    }
    /**
     * 身份证验证
     * @param $cardno
     * @return mixed|string
     */
    static public function idCardQuery($idCard,$realName)
    {
        self::init();
        $params = array(
            "idcard"    => $idCard,        //身份证号码
            "realname"  => $realName,       //返回数据格式：json或xml,默认json
            "key"       => self::$idKey,     //你申请的key
        );
        $paramString = http_build_query($params);
        $url = self::$url;
        $content = self::juhecurl($url,$paramString);
        $result = json_decode($content,true);
        if($result){
            if($result['error_code']=='0'){
                return $result['result'];
            }else{
                $error = $result['error_code'].":".$result['reason'];
                log::alert($error);
                return false;
            }
        }else{
            log::notice("--------请求失败---------");
            log::alert($result);
            return false;
        }
    }

    static public function imgIdCard($img,$side)
    {
        self::init();
        $params = array(
            "image"=>$img,      //图像数据，base64编码(不包含data:image/jpeg;base64,)，要求base64编码后大小不超过4M，最短边至少15px，最长边最大4096px,支持jpg/png/bmp格式
            "side"=>$side,      //front:正面识别;back:反面识别;
            "key" => self::$imgKey,     //你申请的key
        );
        $paramString = http_build_query($params);
        $content = self::Juhecurl(self::$img_url,$paramString,1);
        $result = json_decode($content,true);
        if($result){
            if($result['error_code'] == 0){
                return $result['result'];
            }else{
                $error = $result['error_code'].":".$result['reason'];
                log::alert($error);
                return false;
            }
        }else{
            log::notice("--------请求失败---------");
            log::alert($result);
            return false;
        }
    }

    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    static function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
}