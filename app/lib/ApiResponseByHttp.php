<?php

namespace App\lib;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;

/**
 *  通用API
 *      返回值的规范    responseMessage
 *      分页          page
 *      获取IP        getIp
 *      获取路由参数   getUrlParameter
 * Class        :Api
 * @package     :App\lib
 */
class ApiResponseByHttp extends YnAbstractApi
{
    /**
     * 规范接口返回值
     * @param int $code
     * @param null $data
     * @param string $message
     * @return mixed
     */
    public function responseMessage($code = 0, $data = null, $message = '')
    {
        $code = (int)$code;
        if (!isset($data)) {
            // $data没有初始化（变量未知或为NULL），默认空字符
            $data = '';
        }

        $message = trim($message . '');
        if ($message == '') {
            $message = $this->getCodeDescription($code);
        }

        return Response::json([
            "code" => $code,
            "data" => $data,
            "message" => $message
        ]);

    }
    
    /**
     * 分页
     * @param array $res 结果
     * @param int $page 当前页
     * @param int $pageNum 每页个数
     * @return LengthAwarePaginator
     */
    public function page($res = [], $page = 1, $pageNum = 10, array $options = [])
    {
        return new LengthAwarePaginator(array_slice($res, ($page - 1) * $pageNum, $pageNum), count($res), $pageNum, $page, $options);
    }

}