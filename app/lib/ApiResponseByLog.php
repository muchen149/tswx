<?php

namespace App\lib;

use Illuminate\Support\Facades\Log;

class ApiResponseByLog extends YnAbstractApi
{
    /**
     * 返回的信息记录成日志形式
     * @param int $code
     * @param null $data
     * @param string $message
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

        if ($code == 0) {
            Log::info('执行成功! 成功状态码:' . $code . '    成功返回的数据:' . $data . '    成功信息:' . $message);
        } else {
            Log::error('执行失败! 错误状态码:' . $code . '   错误返回的数据:' . $data . '    错误信息:' . $message);
        }
    }


}