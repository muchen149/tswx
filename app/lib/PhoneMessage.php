<?php
namespace App\lib;

class PhoneMessage
{

    /**
     * @param string $pn phone number
     * @param string $strC message content
     * @return true:error,false:ok. $retMsg contains 'error' or 'ok'.
     **/

    static function sendPhoneMessage($pn, $strC, &$retMsg)
    {

        //php curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('register.SENDMSGURL'));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, config('register.APIKEY'));

        curl_setopt($ch, CURLOPT_POST, TRUE);
        $msgArray = array('mobile' => $pn, 'message' => config('register.MSGHEADCONTENT') . $strC . config('register.MSGFOOTCONTENT'));

        curl_setopt($ch, CURLOPT_POSTFIELDS, $msgArray);

        unset($resError);
        unset($resObj);

        try {
            $res = curl_exec($ch);
            $resError = curl_error($ch);
            curl_close($ch);
        } catch (Exception $e) {
            $retMsg = "error";
            return true;
        }
        if ($res == false) {     //false,must be error
            $retMsg = "error";
            return true;
        }

        try {
            $resObj = json_decode($res);
        } catch (Exception $e) {
            $retMsg = "error";
            return true;
        }
        if (!isset($resObj)) {
            $retMsg = "error";
            return true;
        }
        if (0 !== ($resObj->{'error'})) {
            $retMsg = 'error';
            return true;
        }
        return false;
    }

    /**
     * @param string $pn phone number
     * @param string $strC message content
     * @return true:error,false:ok. $retMsg contains 'error' or 'ok'.
     **/

    static function sendPhoneMessageFroReturn($pn, $strC, &$retMsg)
    {

        //php curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('register.SENDMSGURL'));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, config('register.APIKEY'));

        curl_setopt($ch, CURLOPT_POST, TRUE);
        $msgArray = array('mobile' => $pn, 'message' => $strC . config('register.MSGFOOTCONTENT'));

        curl_setopt($ch, CURLOPT_POSTFIELDS, $msgArray);

        unset($resError);
        unset($resObj);

        try {
            $res = curl_exec($ch);
            $resError = curl_error($ch);
            curl_close($ch);
        } catch (Exception $e) {
            $retMsg = "error";
            return true;
        }
        if ($res == false) {     //false,must be error
            $retMsg = "error";
            return true;
        }

        try {
            $resObj = json_decode($res);
        } catch (Exception $e) {
            $retMsg = "error";
            return true;
        }
        if (!isset($resObj)) {
            $retMsg = "error";
            return true;
        }
        if (0 !== ($resObj->{'error'})) {
            $retMsg = 'error';
            return true;
        }
        return false;
    }

    /**
     * @param none
     * @return  true denote error, counts denote deposits
     **/

    static function getMsgServiceStatus(& $counts)
    {
        //php curl
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, config('register.STATUSMSGURL'));
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, config('register.APIKEY'));

        unset($resError);
        unset($resObj);

        try {
            $res = curl_exec($ch);   //
            $resError = curl_error($ch);
            curl_close($ch);
        } catch (Exception $e) {
            $counts = -1;
            return ($resError);
        }
        if ($res == false) {     //false,must be error
            $counts = -1;
            return true;
        }

        try {
            $resObj = json_decode($res);  //CURLOPT_RETURNTRANSFER设置为TRUE，可能返回401页面，而不是JSON字符串
        } catch (Exception $e) {
            $counts = -1;
            return true;
        }
        if (!isset($resObj)) {
            $counts = -1;
            return true;
        }
        $counts = $resObj->{'deposit'};
        return false;
    }

    static function checkPhoneNumRegEx($strPN)
    {

        $len = strlen($strPN);
        if (0 == $len) {     //length is 0, return immediately, not to match, more productiveness.
            return true;
        }
        if (!preg_match('/^1\d{10}$/i', $strPN)) return true;
        return false;
    }

    static function creatMsgCode($len)
    {
        //生成6位数字短信验证码
        $strC = '';
        for ($i = 0; $i < $len; $i++) {
            $strC = $strC . rand(0, 9);
        }
        return $strC;
    }
}