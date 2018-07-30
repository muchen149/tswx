<?php

namespace App\lib;

use GuzzleHttp\Psr7\Request;

abstract class YnAbstractApi implements YnApiInterface
{
    /**
     *
     * @param int $code
     * @param null $data
     * @param string $message
     * @return mixed
     */
    abstract public function responseMessage($code = 0, $data = null, $message = '');

    /**
     * 获取返回状态码描述信息
     * @param $code
     * @return mixed|string
     */
    public function getCodeDescription($code)
    {
        $codes = array(
            // 常规代码，代码文件为：./config/errcode.php
            $code => config('errcode.' . $code),
        );
        $result = (isset($codes[$code])) ? $codes[$code] : '未知的执行状态代码';
        return $result;
    }

    /**
     * 获取用户ID 地址
     * @return  mixed
     */
    public function getIp()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');

        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * 获取上个路由中的参数
     *  例如 http://shuo.ittun.com/ynmo/public/?a=1  获取到：1
     * @param Request $request
     * @return  mixed
     */
    public function getUrlParameter($request)
    {
        $url = $request->getRequestUri();
        $str = strrchr($url, '=');
        $str = substr($str, 1);
        return $str;
    }

}