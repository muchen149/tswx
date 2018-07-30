<?php
namespace App\controllers\elife;
use App\facades\Api;
use App\icbc\DefaultIcbcClient;
use App\icbc\IcbcConstants;
use App\icbc\UiIcbcClient;
use App\Jobs\AutoDismantleOrder;
use App\icbc\IcbcSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\icbc\IcbcEncrypt;
class ICBCController extends BaseController {
    /**
     * icbc elife notifey
     * @biz_content Request $request
     * @return mixed
     * 通知接口
     */
    public function notify(Request $request) {
		$datas = $request->input();
		//转换格式
		$params = json_decode($datas['biz_content'],true);
		//修改订单状态
		if ($params['return_code'] == 0) {

			DB::table('order')->where('plat_order_sn',$params['out_trade_no'])->update(array("pay_rmb_sn"=>$params['order_id'],"plat_order_state"=>2,'pay_rmb_amount'=>$params['payment_amt'] * 0.01,"pay_rmb_time"=>$params['pay_time']));
		}
		$data['return_code']   = $params['return_code'];
		$data['cust_id']       = $params['cust_id'];
		$data['return_msg']    = $params['return_msg'];
		$data['msg_id'] 	   = $params['msg_id'];
		$data['card_no'] 	   = $params['card_no'];
		$data['total_amt'] 	   = $params['total_amt'];
		$data['point_amt'] 	   = $params['point_amt'];
		$data['ecoupon_amt']   = $params['ecoupon_amt'];
		$data['mer_disc_amt']  = $params['mer_disc_amt'];
		$data['coupon_amt']    = $params['coupon_amt'];
		$data['bank_disc_amt'] = $params['bank_disc_amt'];
		$data['payment_amt']   = $params['payment_amt'];
		$data['out_trade_no']  = $params['out_trade_no'];
		$data['order_id'] 	   = $params['order_id'];
		$data['pay_time'] 	   = $params['pay_time'];
		$data['total_disc_amt']= $params['total_disc_amt'];
		$data['mer_id'] 	   = $params['mer_id'];
		$data['attach'] 	   = $params['attach'];
		$data['content'] 	   = serialize($params);
		//IcbcSignature::verify();
		//录入日志信息
		$result = DB::table('biz_content_log')->insert($data);
		$getPlatOrderId = DB::table('order')->where('plat_order_sn',$params['out_trade_no'])->first(['plat_order_id']);
		//初始化
		$ch = curl_init();
		// 设置URL和中转接口
		//curl_setopt($ch, CURLOPT_URL, "http://tswx.shuitine.com/pub_route/order/split/884");//.$getPlatOrderId->plat_order_id);
		curl_setopt($ch, CURLOPT_URL, "http://tswx.shuitine.com/pub_route/order/split/".$getPlatOrderId->plat_order_id);
		curl_setopt($ch, CURLOPT_HEADER, false);
		// 抓取URL并把它传递给浏览器
		$data = curl_exec($ch);
		echo $data;
		//派单到第三方
		//curl_setopt($ch, CURLOPT_URL, "http://tswx.shuitine.com/thirdapi/wyyx/sendManual/884");//.$getPlatOrderId->plat_order_id);
		curl_setopt($ch, CURLOPT_URL, "http://tswx.shuitine.com/thirdapi/wyyx/sendManual/".$getPlatOrderId->plat_order_id);
		curl_setopt($ch, CURLOPT_HEADER, false);
		echo $data;
		//关闭cURL资源，并且释放系统资源
		curl_close($ch);
    }

    /**
     * icbc elife notifey
     * @param Request $request
     * @return mixed
     * 退款结果查询
     */
    public function rejectQuery()
    {
        $request = array(
            "serviceUrl" => 'https://apipcs3.dccnet.com.cn/api/qrcode/V2/reject/query',
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "mer_id"=>"020099161357",
                "cust_id"=>"Ey0LsS39FwdE35TsSW1A2eeoIRMZrjSa",
                "out_trade_no"=>"X000000001",
                "order_id"=>"020004042266201701030000001",
                "reject_no"=>"X000000001"
            )
        );
        //以下构造函数第1个参数为appid，第2个参数为RSA密钥对中私钥，第6个参数为API平台网关公钥
        $client = new DefaultIcbcClient('10000000000000059530',
            'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAIJBzZNaiobplRUgtJ4OzmUvZRK99ih/fUyDBOoFLtpJJCCRzp8T6V11YNlE7Xg5dt+EG7byQs2NImqg0eWEj/mBdZ7UmlAct7BNw2hyF2h4R5OEfXyjoH3wqGjKJayhaHTvLM1DYy/mDFBb0ShJYI1QMScQJZhsOhMMFhrrZwIZAgMBAAECgYAA2kdrOIOBoJPOQJmOE1C8jtPdjIrI9xSt5Imqsn/9A8+NuwacOfgkGXmZ0n6vc8jYa7f2uZ1AVTUtd4IIO5bpq8s0Tw2BfWALYwr/JdUuNKSjHVQsh/Do+wl8BgOgB4RqsNXWNGtoMC8lHKHmrVcpyJMfDc3cP07NZ1wG2zB0lQJBAM+dNZv2L/Z4RzvQcoVZEthYavZ4pkFoWGYC4jwc5G8um76zoQyrtxWYrtTP0GS+xFFX2dEuiGXxwzmSQJrPdrMCQQCgnUXcQe/if2c6TFt4x9v+6L0pmFClYyiOi9RuBSz1sHmPouuc/YYvuxAOdOzu3yzOkeo7b5KcCKITTWvKI+oDAkA5dl6vIw2VXycAJCp+Q/AWVyqLu0rw0Yud+HBbiPek2jabKqaJlkFfRdol5rrcF3zIstMDtahk5uxM0/DzqDZHAkBGnZ8vfdYIUVeDbDrzWXvCEXXJqewbKwOT2KqnTKM9yj9IBatttJGgvrAKiyH4zCqZD9JaG23sKGeJ8QopL60dAkEAtc4tlKoj3XZzRUXboqz0EhkgkjzDj50zpCD1sJKZ2EZH+A/7tXwPug+RnuSmKpM2uv3msqw3prdS3K4En8+rog==',
            IcbcConstants::$SIGN_TYPE_RSA2,
            '',
            '',
            'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoFpnnCB19+v2oWxQVsUQ8KTC47uQRCZVcve6vkZDmmW2HR2TdYo4WXzYhrmadg83IWYhQOyWali6Fypcv9eYYUlL2rTec65hru/kHqGFAS8LPg12ulnnzLmMAMwfbNPdxI/evwQHFK9TkJB2BhOut9REfTURr4Ps5F/mT6QlH+7NSwux8ahir1odZmjUo1Nurgs3uoxFnCtsejcuDTlaevs5Q5q7mpekuFHcolVehB/XkYecEbXT38ODvawJwNrz9HrtGhuIAc61P09n3j7RlwVva+ew0SMICLzL5L8EyvihinBmPn/7KpGiQHcRKO6zMtMaD3AoW6XsHCg4x4lJhwIDAQAB',
            '',
            '',
            '',
            '');
		//执行调用;msgId消息通讯唯一编号，要求每次调用独立生成，APP级唯一
        $resp = $client->execute($request,'msgId','');
        Log::notice($resp);
        $respObj = json_decode($resp,true);
        if($respObj["return_code"] == 0){
            echo $respObj["return_msg"];
        }else{
            echo $respObj["return_msg"];
        }
    }

    /**
     * icbc elife notifey
     * @param Request $request
     * @return mixed
     * 退款
     */
    public function reject(Request $request) {
		$getOrderSn = DB::table('order')->where('plat_order_id',$request->input('data'))->first(['plat_order_sn']);
		// AES密钥
		$MY_AES_KEY ='InAop3i4KTAXjsSKI1CTsg==';
		//appId
		$APP_ID ='10000000000000059530';
		//私钥
		$MY_PRIVATE_KEY = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCgWmecIHX36/ahbFBWxRDwpMLju5BEJlVy97q+RkOaZbYdHZN1ijhZfNiGuZp2DzchZiFA7JZqWLoXKly/15hhSUvatN5zrmGu7+QeoYUBLws+DXa6WefMuYwAzB9s093Ej96/BAcUr1OQkHYGE6631ER9NRGvg+zkX+ZPpCUf7s1LC7HxqGKvWh1maNSjU26uCze6jEWcK2x6Ny4NOVp6+zlDmrual6S4UdyiVV6EH9eRh5wRtdPfw4O9rAnA2vP0eu0aG4gBzrU/T2fePtGXBW9r57DRIwgIvMvkvwTK+KGKcGY+f/sqkaJAdxEo7rMy0xoPcChbpewcKDjHiUmHAgMBAAECggEBAJfeJ2TJpZChzVqCz+/uAiY3lVDEElVJDQKutxGAUISJMhqPKVpYBxhR0mx+mliX/nnGVVY8/BRKZiyMdX1H/kydc5b2V/ytulxJXP7ZsLM3T+l8LOc/QPc2/+69ZEHYwp9oNukoMmCX0IgJGY6V05LNGfSPb2mQg6qjXOguqO585+ciOGGpbRZbD12BXwWiSR8CZzJKE8vAMAVJu+L+ncTSoi2RpDQkJ+C7fgXdRTIPu3+5dDAanmd5Zn4c83C76BSFxOh83ql1nGCjmj36fhyA5Z288aZZbe7w7nclg4Jt1q63Z372E6h5YqRqItSCzF9mKEi6AdQMbgALiX9hkXECgYEAzVXZEKROYRBHw5utM3kYpVZ1uP9z/n9RPsjjSgcqOqYBUMy7aH5odLmw446xPiH5cnIbC13DU8Tol+9G8gnZ4xoAn1mtuw7WJ/exP7Q/pE6v83QGZ3PaX0AQEYHqWHJKFzLh6lOh8Y3mEdRMmyVAHSNz3gq0WFHOFNjnUo7P0e8CgYEAx+s5b/p63vTShaQCdvSU7VwSxR6tqR1Bq4Cxe1m+/dUU737lKFUQImz6CpI19l/k6sxUQqEJmWyqDguURAZpHjcAgdWFsFDc2lCnj6TXG4j8CXeO3cK3RYT360oMDKXzqEdwGj/Ief4Vzy5M1lNgQEmOtUTyJVCwBwWUGXSrOekCgYBPuBjCIUhc3tk91F72MPmkl2C1Jlh+YifE3HGB+C4o/vJb0GCiPRGI398ROgEOQlp6WFqvmwOOrlAvTLKancB+L0Y2l7affS8f7UZfmTdsLzCYsF8cIxqRCGo0od+93wFs6FBVjYq+IX1FRstHILs3lOATQMyrzXbZGS0WHGQK+QKBgFlqW8Y5wbr2xTIAqRmLSxDenYaMsh9xdm2+oaMKAOKG61Yy60uewBilpTAVNQ181mYt/YHPhPuaHnUpuKa0N0/MSe3IEoNJp339lPQqRguKuS+CyeNls5LkZf5WoA0ILHKXgQw8eu4VNqvziWpS4DngrHNm4ubNr+10EUlRZUQBAoGAM+I7uTREEvl1ngMzPaFL/c50RzwU0Dig78HIH2ebGFOGHJJKH6lnzA4zmRm2Ic58z6E27NQcv/J2P/9gA0I6hiMycEXcClaDsBFpR6wGA1oiBVnvcTYnpNUhed34RtxothWG4s8vwbaJYAo4JKcHidopiBvBAbis4ziNsZr7GyU=';
		//网关公钥
		$MY_PUBLIC_KEY = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCwFgHD4kzEVPdOj03ctKM7KV+16bWZ5BMNgvEeuEQwfQYkRVwI9HFOGkwNTMn5hiJXHnlXYCX+zp5r6R52MY0O7BsTCLT7aHaxsANsvI9ABGx3OaTVlPB59M6GPbJh0uXvio0m1r/lTW3Z60RU6Q3oid/rNhP3CiNgg0W6O3AGqwIDAQAB';
		$msgId = md5(uniqid());
		//退款编号
		$rejectSn = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $request = array(
            "serviceUrl" => 'https://apipcs3.dccnet.com.cn/api/qrcode/V2/reject',
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "mer_id"=>"020004042266",
                "cust_id"=>"NZRfxMkpvrivhzv0gVDS4JHD4G6LkhG6",//该字段非必输项 用户唯一标识
                "out_trade_no"=>$getOrderSn->plat_order_sn,	  //该字段非必输项,out_trade_no和order_id选一项上送即可
                "order_id"=>"",    					  		  //该字段非必输项,out_trade_no和order_id选一项上送即可
                "reject_no"=>$rejectSn,  			  		  //退款编号
                "reject_amt"=>$getOrderSn->pay_rmb_amount,    //退款金额
                "oper_id"=>""                         		  //该字段非必输项 操作人员ID
            )
        );
        //以下构造函数第1个参数为appid，第2个参数为RSA密钥对中私钥，第6个参数为API平台网关公钥
        $client = new DefaultIcbcClient(
			$APP_ID,
			$MY_PRIVATE_KEY,
			IcbcConstants::$SIGN_TYPE_RSA2,
            '',
            '',
			$MY_PUBLIC_KEY,
            $MY_AES_KEY,
            IcbcConstants::$ENCRYPT_TYPE_AES,
            '',
            '');
		//执行调用;msgId消息通讯唯一编号，要求每次调用独立生成，APP级唯一
        $resp = $client->execute($request,$msgId,'');
		$respObj = json_decode($resp,true);
        if ($respObj["return_code"] == 0) {
			//查询系统是否已经退款
			$getOrderState = DB::table('order')->where('plat_order_sn','=',$respObj['out_trade_no'])->first();
			if ($getOrderState->plat_order_state == '-9') {
				//禁止重复操作
				Log::error('错误状态:1 --- 错误说明:退款有误');
				return view('errors.error');
			} else {
				DB::table('order')->where('plat_order_sn',$respObj['out_trade_no'])->update(array("plat_order_state"=>'-2'));
				//添加退款记录
				$data['return_code'] 	      = $respObj['return_code'];
				$data['msg_id'] 	          = $respObj['msg_id'];
				$data['reject_total_disc_amt']= $respObj['reject_total_disc_amt'];
				$data['return_msg']           = $respObj['return_msg'];
				$data['card_no']              = $respObj['card_no'];
				$data['reject_point']         = $respObj['reject_point'];
				$data['out_trade_no']         = $respObj['out_trade_no'];
				$data['order_id']             = $respObj['order_id'];
				$data['reject_ecoupon'] 	  = $respObj['reject_ecoupon'];
				$data['real_reject_amt'] 	  = $respObj['real_reject_amt'];
				$data['reject_amt'] 	      = $respObj['reject_amt'];
				$data['cust_id'] 	          = $respObj['cust_id'];
				$data['reject_mer_disc_amt']  = $respObj['reject_mer_disc_amt'];
				$data['reject_no']            = $respObj['reject_no'];
				$data['reject_bank_disc_amt'] = $respObj['reject_bank_disc_amt'];
				$data['content'] 	          = serialize($respObj);
				$data['created_time'] 	      = $data['updated_time'] = time();
				//录入日志信息
				$result = DB::table('e_refund')->insert($data);
			}
			    return Api::responseMessage(0);
			} else {
			Log::error('错误状态:1 --- 错误说明:退款有误');
            return view('errors.error');       
		}
    }

    /**
     * icbc elife notifey
     * @param Request $request
     * @return mixed
     * 冲正
     */
    public function reverse()
    {
        $request = array(
            "serviceUrl" => 'https://apipcs3.dccnet.com.cn/api/qrcode/V2/reverse',
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "mer_id"=>"020004042266",
                "cust_id"=>"Ey0LsS39FwdE35TsSW1A2eeoIRMZrjSa",//该字段非必输项
                "out_trade_no"=>"X000000001",
                "order_id"=>"",  //该字段非必输项
                "reject_no"=>"", //该字段非必输项
                "reject_amt"=>"",//该字段非必输项
                "oper_id"=>""    //该字段非必输项
            )

        );
        //以下构造函数第1个参数为appid，第2个参数为RSA密钥对中私钥，第6个参数为API平台网关公钥
        $client = new DefaultIcbcClient('10000000000000059530',
            'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAIJBzZNaiobplRUgtJ4OzmUvZRK99ih/fUyDBOoFLtpJJCCRzp8T6V11YNlE7Xg5dt+EG7byQs2NImqg0eWEj/mBdZ7UmlAct7BNw2hyF2h4R5OEfXyjoH3wqGjKJayhaHTvLM1DYy/mDFBb0ShJYI1QMScQJZhsOhMMFhrrZwIZAgMBAAECgYAA2kdrOIOBoJPOQJmOE1C8jtPdjIrI9xSt5Imqsn/9A8+NuwacOfgkGXmZ0n6vc8jYa7f2uZ1AVTUtd4IIO5bpq8s0Tw2BfWALYwr/JdUuNKSjHVQsh/Do+wl8BgOgB4RqsNXWNGtoMC8lHKHmrVcpyJMfDc3cP07NZ1wG2zB0lQJBAM+dNZv2L/Z4RzvQcoVZEthYavZ4pkFoWGYC4jwc5G8um76zoQyrtxWYrtTP0GS+xFFX2dEuiGXxwzmSQJrPdrMCQQCgnUXcQe/if2c6TFt4x9v+6L0pmFClYyiOi9RuBSz1sHmPouuc/YYvuxAOdOzu3yzOkeo7b5KcCKITTWvKI+oDAkA5dl6vIw2VXycAJCp+Q/AWVyqLu0rw0Yud+HBbiPek2jabKqaJlkFfRdol5rrcF3zIstMDtahk5uxM0/DzqDZHAkBGnZ8vfdYIUVeDbDrzWXvCEXXJqewbKwOT2KqnTKM9yj9IBatttJGgvrAKiyH4zCqZD9JaG23sKGeJ8QopL60dAkEAtc4tlKoj3XZzRUXboqz0EhkgkjzDj50zpCD1sJKZ2EZH+A/7tXwPug+RnuSmKpM2uv3msqw3prdS3K4En8+rog==',
            IcbcConstants::$SIGN_TYPE_RSA2,
            '',
            '',
            'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoFpnnCB19+v2oWxQVsUQ8KTC47uQRCZVcve6vkZDmmW2HR2TdYo4WXzYhrmadg83IWYhQOyWali6Fypcv9eYYUlL2rTec65hru/kHqGFAS8LPg12ulnnzLmMAMwfbNPdxI/evwQHFK9TkJB2BhOut9REfTURr4Ps5F/mT6QlH+7NSwux8ahir1odZmjUo1Nurgs3uoxFnCtsejcuDTlaevs5Q5q7mpekuFHcolVehB/XkYecEbXT38ODvawJwNrz9HrtGhuIAc61P09n3j7RlwVva+ew0SMICLzL5L8EyvihinBmPn/7KpGiQHcRKO6zMtMaD3AoW6XsHCg4x4lJhwIDAQAB',
            '',
            '',
            '',
            '');
		//执行调用;msgId消息通讯唯一编号，要求每次调用独立生成，APP级唯一
        $resp = $client->execute($request,'msgId','');
        echo $resp;
        $respObj = json_decode($resp,true);
        if($respObj["return_code"] == 0){ 
            echo $respObj["return_msg"];
        }else{
            echo $respObj["return_msg"];
        }
    }

    /**
     * icbc elife notifey
     * @param Request $request
     * @return mixed
     * 工银E支付
     */
    public function pay(Request $request) {
		$member = Auth::user();
		//再次付款 根据类型判读来源数据类型
		if ($request->input('type') == 'pay_again') {
			$request->plat_order_id = $request['plat_order_id'];
		}
		if (!$request->plat_order_id) {
			Log::error('错误状态:10001 --- 错误说明:参数错误');
            return view('errors.error');
		}
		$elifeOrder  = DB::table('order')->where('plat_order_id',$request->plat_order_id)->first(['plat_order_id','member_mobile','plat_order_sn','create_time','pay_rmb_amount']);
		$ordeName    = DB::table('order_goods')->where('plat_order_id',$elifeOrder->plat_order_id)->first(['sku_name']);
		//分行配置参数
		if ($member->entrance_type == 1) {
			$mer_id = '020099161357';
			$store_code = '02000347805';
		//总行A配置参数
		} else if ($member->entrance_type == 2) {
			$mer_id = '020099161356';
			$store_code = '02000347802';
		//总行B配置参数
		} else if ($member->entrance_type == 3) {
			$mer_id = '020099161350';
			$store_code = '02000345400';
		} else {
			Log::error('错误状态:10001 --- 错误说明:参数错误');
            return view('errors.error');
		}
		//测试
		$mer_id = '020004042266';
		$store_code = '02000319741';
		// AES密钥 
		//测试 InAop3i4KTAXjsSKI1CTsg== 
		//生产 X3KBHDDEXPpB++JNQ0p42g==
		$MY_AES_KEY ="InAop3i4KTAXjsSKI1CTsg==";
		//appId 
		//测试 10000000000000059530 
		//生产 10000000000000246011
		$APP_ID ="10000000000000059530";
		//私钥 
		//测试 MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCgWmecIHX36/ahbFBWxRDwpMLju5BEJlVy97q+RkOaZbYdHZN1ijhZfNiGuZp2DzchZiFA7JZqWLoXKly/15hhSUvatN5zrmGu7+QeoYUBLws+DXa6WefMuYwAzB9s093Ej96/BAcUr1OQkHYGE6631ER9NRGvg+zkX+ZPpCUf7s1LC7HxqGKvWh1maNSjU26uCze6jEWcK2x6Ny4NOVp6+zlDmrual6S4UdyiVV6EH9eRh5wRtdPfw4O9rAnA2vP0eu0aG4gBzrU/T2fePtGXBW9r57DRIwgIvMvkvwTK+KGKcGY+f/sqkaJAdxEo7rMy0xoPcChbpewcKDjHiUmHAgMBAAECggEBAJfeJ2TJpZChzVqCz+/uAiY3lVDEElVJDQKutxGAUISJMhqPKVpYBxhR0mx+mliX/nnGVVY8/BRKZiyMdX1H/kydc5b2V/ytulxJXP7ZsLM3T+l8LOc/QPc2/+69ZEHYwp9oNukoMmCX0IgJGY6V05LNGfSPb2mQg6qjXOguqO585+ciOGGpbRZbD12BXwWiSR8CZzJKE8vAMAVJu+L+ncTSoi2RpDQkJ+C7fgXdRTIPu3+5dDAanmd5Zn4c83C76BSFxOh83ql1nGCjmj36fhyA5Z288aZZbe7w7nclg4Jt1q63Z372E6h5YqRqItSCzF9mKEi6AdQMbgALiX9hkXECgYEAzVXZEKROYRBHw5utM3kYpVZ1uP9z/n9RPsjjSgcqOqYBUMy7aH5odLmw446xPiH5cnIbC13DU8Tol+9G8gnZ4xoAn1mtuw7WJ/exP7Q/pE6v83QGZ3PaX0AQEYHqWHJKFzLh6lOh8Y3mEdRMmyVAHSNz3gq0WFHOFNjnUo7P0e8CgYEAx+s5b/p63vTShaQCdvSU7VwSxR6tqR1Bq4Cxe1m+/dUU737lKFUQImz6CpI19l/k6sxUQqEJmWyqDguURAZpHjcAgdWFsFDc2lCnj6TXG4j8CXeO3cK3RYT360oMDKXzqEdwGj/Ief4Vzy5M1lNgQEmOtUTyJVCwBwWUGXSrOekCgYBPuBjCIUhc3tk91F72MPmkl2C1Jlh+YifE3HGB+C4o/vJb0GCiPRGI398ROgEOQlp6WFqvmwOOrlAvTLKancB+L0Y2l7affS8f7UZfmTdsLzCYsF8cIxqRCGo0od+93wFs6FBVjYq+IX1FRstHILs3lOATQMyrzXbZGS0WHGQK+QKBgFlqW8Y5wbr2xTIAqRmLSxDenYaMsh9xdm2+oaMKAOKG61Yy60uewBilpTAVNQ181mYt/YHPhPuaHnUpuKa0N0/MSe3IEoNJp339lPQqRguKuS+CyeNls5LkZf5WoA0ILHKXgQw8eu4VNqvziWpS4DngrHNm4ubNr+10EUlRZUQBAoGAM+I7uTREEvl1ngMzPaFL/c50RzwU0Dig78HIH2ebGFOGHJJKH6lnzA4zmRm2Ic58z6E27NQcv/J2P/9gA0I6hiMycEXcClaDsBFpR6wGA1oiBVnvcTYnpNUhed34RtxothWG4s8vwbaJYAo4JKcHidopiBvBAbis4ziNsZr7GyU=
		//问题生产 MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDB2LsU/8MH1w5ynXjuLBIcrGGvwaP/tS3p0VOJvsX+yzj/YJgBRpq8uDGf30SgM6g6baZlBsNdz3DPEsXhWc8eeSEykGd6rObfxrOFKi/ZfxyAxyazEKOhH4Fg6TQHHUdWA+eCJGVZ5djq7pbJjn65jzF/PagaSlNtHg7Mm/hqYOfr7dHL8V0issNPK8emg69vvVCtlKokdaO/WDK/0DVaAEmCVUdCucJjcG++aox+cvfFZ84e+301zLJpl/GFJh+sJMwg/JgqBfCa6Gon20cPsIkjnCkkoemviETH0agmV3LJTNSHV9aTtr84/NZsU2LRJ98GcCyKEz53v+N+GuV5AgMBAAECggEBALiSJWfUMx5G7ZHCUL/upHw6wnS32vCB0aq9Tl5skFePpsC6d2FXmcQxWcbQrqYfKYIgn3u4GdM7zjzMsi9k9OoeCbb8ehr7gIT3zDzyM0dTWY9gkH+VS0gCGI1u3sQbY64J36gyAXK8gHMJI/fcPttt9YIi8em+dXsDULIPnFOkMziBFS7ES4MNSG+9b2oSdHYiUCgXMRroSm3nQ54HJANV94Lv0OA4fv87X68pj3lchjyL+FAs4WLad6wPYPvOsEQkpbwWmoC2ZG2B/xPp2s8hq1T2H/sqj8aBg+1qdPlDQM5UQOnisnMmcd5HsXPkmBc0JQVswmW/RYSwRqm91IECgYEA5CRwCGmbvpnSRpzFoKgecztcjbCc1hf6/qsRxt7oICByWsa/jmV1hg5VTg/+nrpWEjb6FN4COLusMtbDxsHFnSWK2OXnBT/GSRKdiTQyNcsTSI4aL7QI7JP2JYp5n5BYtWBSHgoatH6ObnBlhWfepAq3ADrw9nDt8gBLspJtiFECgYEA2YQ71xyYGFL5ms0IcUJZinYhMCueyXGBbdJk8ioXj5rAOtSUfPBg6UdKleP+wB86uCuNrsRoo8qfZyH/X9cuXFHlnO14yUnKy0+Jaz07Fa+8mxQUiU8kw+G1PNXYL4+Qxrdchxi5n0Agorc29lIxr5OD/9Ntjg3AFK1Jpp37aKkCgYEAm6s5PqRA/ycaUdA7EUplrJ7+oje2bGdkfkzgDmCe5vz8ym/+2Pzl2hkBoAhN5qSigj89GOv7fBaigvYEFCS34Ghze1gz8GL5u3aWQ7533Di66bD8sTwZMsQrGqaIIpZt0STuI00lt677JORQgVzEkA43nFKbhKy9z3jgLfK4BmECgYEAjmJ1GXbqSOHn6mFUqW8ZVf3F89ZZ9tuKMe2Ljsnm6mYOd3Q4TyC7D3lPZZb9Z54Jvg0kjcacCLvWZJhopsAg5OXnPDZm3ryjNzs1ZTGLv8Jt38XhO5DHLEJPdSc2gMulpa7ZrsWMnKZJtrngDJX5WElkGs635Ekz91UH5T19GSECgYBfWCMnS8L54i8aB1VGP+/lXWOvQMnSlkN6b2dtdzsxJ+qwbP8l6AaH2Tz6lc3a6B9y5YBTwRdq0EtGXUEPOtNPRAvr9XhlmHK+CEuQRtx6HwbWtq+7158dbo+SvbprbqjgKzmhMGal+nBouCnPuk7n9E1ucyvST3E08dT29jIQKA==
		$MY_PRIVATE_KEY = "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCgWmecIHX36/ahbFBWxRDwpMLju5BEJlVy97q+RkOaZbYdHZN1ijhZfNiGuZp2DzchZiFA7JZqWLoXKly/15hhSUvatN5zrmGu7+QeoYUBLws+DXa6WefMuYwAzB9s093Ej96/BAcUr1OQkHYGE6631ER9NRGvg+zkX+ZPpCUf7s1LC7HxqGKvWh1maNSjU26uCze6jEWcK2x6Ny4NOVp6+zlDmrual6S4UdyiVV6EH9eRh5wRtdPfw4O9rAnA2vP0eu0aG4gBzrU/T2fePtGXBW9r57DRIwgIvMvkvwTK+KGKcGY+f/sqkaJAdxEo7rMy0xoPcChbpewcKDjHiUmHAgMBAAECggEBAJfeJ2TJpZChzVqCz+/uAiY3lVDEElVJDQKutxGAUISJMhqPKVpYBxhR0mx+mliX/nnGVVY8/BRKZiyMdX1H/kydc5b2V/ytulxJXP7ZsLM3T+l8LOc/QPc2/+69ZEHYwp9oNukoMmCX0IgJGY6V05LNGfSPb2mQg6qjXOguqO585+ciOGGpbRZbD12BXwWiSR8CZzJKE8vAMAVJu+L+ncTSoi2RpDQkJ+C7fgXdRTIPu3+5dDAanmd5Zn4c83C76BSFxOh83ql1nGCjmj36fhyA5Z288aZZbe7w7nclg4Jt1q63Z372E6h5YqRqItSCzF9mKEi6AdQMbgALiX9hkXECgYEAzVXZEKROYRBHw5utM3kYpVZ1uP9z/n9RPsjjSgcqOqYBUMy7aH5odLmw446xPiH5cnIbC13DU8Tol+9G8gnZ4xoAn1mtuw7WJ/exP7Q/pE6v83QGZ3PaX0AQEYHqWHJKFzLh6lOh8Y3mEdRMmyVAHSNz3gq0WFHOFNjnUo7P0e8CgYEAx+s5b/p63vTShaQCdvSU7VwSxR6tqR1Bq4Cxe1m+/dUU737lKFUQImz6CpI19l/k6sxUQqEJmWyqDguURAZpHjcAgdWFsFDc2lCnj6TXG4j8CXeO3cK3RYT360oMDKXzqEdwGj/Ief4Vzy5M1lNgQEmOtUTyJVCwBwWUGXSrOekCgYBPuBjCIUhc3tk91F72MPmkl2C1Jlh+YifE3HGB+C4o/vJb0GCiPRGI398ROgEOQlp6WFqvmwOOrlAvTLKancB+L0Y2l7affS8f7UZfmTdsLzCYsF8cIxqRCGo0od+93wFs6FBVjYq+IX1FRstHILs3lOATQMyrzXbZGS0WHGQK+QKBgFlqW8Y5wbr2xTIAqRmLSxDenYaMsh9xdm2+oaMKAOKG61Yy60uewBilpTAVNQ181mYt/YHPhPuaHnUpuKa0N0/MSe3IEoNJp339lPQqRguKuS+CyeNls5LkZf5WoA0ILHKXgQw8eu4VNqvziWpS4DngrHNm4ubNr+10EUlRZUQBAoGAM+I7uTREEvl1ngMzPaFL/c50RzwU0Dig78HIH2ebGFOGHJJKH6lnzA4zmRm2Ic58z6E27NQcv/J2P/9gA0I6hiMycEXcClaDsBFpR6wGA1oiBVnvcTYnpNUhed34RtxothWG4s8vwbaJYAo4JKcHidopiBvBAbis4ziNsZr7GyU=";
		//网关公钥
		//测试 MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCwFgHD4kzEVPdOj03ctKM7KV+16bWZ5BMNgvEeuEQwfQYkRVwI9HFOGkwNTMn5hiJXHnlXYCX+zp5r6R52MY0O7BsTCLT7aHaxsANsvI9ABGx3OaTVlPB59M6GPbJh0uXvio0m1r/lTW3Z60RU6Q3oid/rNhP3CiNgg0W6O3AGqwIDAQAB
		//生产 MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCMpjaWjngB4E3ATh+G1DVAmQnIpiPEFAEDqRfNGAVvvH35yDetqewKi0l7OEceTMN1C6NPym3zStvSoQayjYV+eIcZERkx31KhtFu9clZKgRTyPjdKMIth/wBtPKjL/5+PYalLdomM4ONthrPgnkN4x4R0+D4+EBpXo8gNiAFsNwIDAQAB
		$MY_PUBLIC_KEY = "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCwFgHD4kzEVPdOj03ctKM7KV+16bWZ5BMNgvEeuEQwfQYkRVwI9HFOGkwNTMn5hiJXHnlXYCX+zp5r6R52MY0O7BsTCLT7aHaxsANsvI9ABGx3OaTVlPB59M6GPbJh0uXvio0m1r/lTW3Z60RU6Q3oid/rNhP3CiNgg0W6O3AGqwIDAQAB";
		//测试请求网址 https://apipcs3.dccnet.com.cn/ui/thirdparty/order/pay/V4
		//生产请求网址 https://gw.open.icbc.com.cn/ui/thirdparty/order/pay/V4
		//APP级唯一
		$msgId = md5(uniqid());
        $request = array(
            "serviceUrl"      => "https://apipcs3.dccnet.com.cn/ui/thirdparty/order/pay/V4",
            "method"          => "POST",
            "isNeedEncrypt"   => true,
            "biz_content"     => array(
				"mer_id"      =>$mer_id,//必输 商户线下档案编号
                "store_code"  =>$store_code, //必输 e生活档案编号
                "cust_id"     =>$member->cust_id,//必输 工行其他接口传递的用户唯一标识
                "out_trade_no"=>$elifeOrder->plat_order_sn,//必输 商户系统订单号
                "order_amt"   =>($elifeOrder->pay_rmb_amount)*100,//必输 订单金额，单位：分
                "trade_date"  =>date("Ymd",$elifeOrder->create_time),//必输 交易日期，格式:yyyyMMdd
                "trade_time"  =>date("ymd",$elifeOrder->create_time),//必输 交易时间，格式: HHmmss
                "attach"	  =>"工银e支付",   //非必输 商户附加数据，最多21个汉字字符，原样返回
                "pay_expire"  =>"1200",     //必输 商户订单有效期，单位：秒，必须小于24小时
                "notify_url"  =>"http://ets.shuitine.com/elife/pay/notify", //非必输 商户接收支付成功通知消息URL,当notify_flag为1时必输
                "notify_flag" =>"1",		 //必输 商户是否开启通知接口 0-否；1-是；非1按0处理
                "auto_submit_flag"=>"0",     //必输 是否买单页自提交标识，0 - 否；1 - 是；非1按0处理
                "goods_name"  =>$ordeName->sku_name,   //非必输 第三方商品名称，最多33个汉字字符
                "all_points_flag"=>"0",      //非必输 全积分抵扣标志，0 - 否，1 - 是；非1按0处理
                "good_points" =>"",          //非必输 兑换商品所需积分，当all_points_flag为1时必输；订单金额与积分兑换比率由业务规则确定
                "installment_flag"=>"0",     //非必输 分期付款标志，0 - 仅全款付款，1 - 仅分期付款；非1按0处理；全积分抵扣标识与分期付款标识不可同时为1
                "installment_times"=>"3",    //非必输 分期期数，当installment_flag为1时必输，为大于1的正整数
                "card_type"   =>"111",       //非必输 支付卡类型，从左往右：第1位：工行信用卡支持标志；第2位：工行借记卡支持标志；第3位：非工行银联卡支持标志。各标志位，0 - 否；1 - 是；非1按0处理
            )
        );
			//以下构造函数第1个参数为appid，第2个参数为RSA密钥对中私钥
			$client = new UiIcbcClient(
			$APP_ID,
			$MY_PRIVATE_KEY,
			IcbcConstants::$SIGN_TYPE_RSA2,
			'',
            '',
			'',
            $MY_AES_KEY,
            IcbcConstants::$ENCRYPT_TYPE_AES,
            '',
            '');
			try{
			//执行调用;msgId消息通讯唯一编号，要求每次调用独立生成，APP级唯一
            $resp = $client->buildPostForm($request,$msgId,'');
            echo $resp;
        }catch(\Exception $e){//捕获异常
            echo 'Exception:'.$e->getMessage()."\n";
        }
    }

    /**
     * icbc elife notifey
     * @param Request $request
     * @return mixed
     * 支付结果查询
     */
    public function qrcodeQuery() {
		// AES密钥
		$MY_AES_KEY ='InAop3i4KTAXjsSKI1CTsg==';
		//appId
		$APP_ID ='10000000000000059530';
		//私钥
		$MY_PRIVATE_KEY = 'MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDB2LsU/8MH1w5ynXjuLBIcrGGvwaP/tS3p0VOJvsX+yzj/YJgBRpq8uDGf30SgM6g6baZlBsNdz3DPEsXhWc8eeSEykGd6rObfxrOFKi/ZfxyAxyazEKOhH4Fg6TQHHUdWA+eCJGVZ5djq7pbJjn65jzF/PagaSlNtHg7Mm/hqYOfr7dHL8V0issNPK8emg69vvVCtlKokdaO/WDK/0DVaAEmCVUdCucJjcG++aox+cvfFZ84e+301zLJpl/GFJh+sJMwg/JgqBfCa6Gon20cPsIkjnCkkoemviETH0agmV3LJTNSHV9aTtr84/NZsU2LRJ98GcCyKEz53v+N+GuV5AgMBAAECggEBALiSJWfUMx5G7ZHCUL/upHw6wnS32vCB0aq9Tl5skFePpsC6d2FXmcQxWcbQrqYfKYIgn3u4GdM7zjzMsi9k9OoeCbb8ehr7gIT3zDzyM0dTWY9gkH+VS0gCGI1u3sQbY64J36gyAXK8gHMJI/fcPttt9YIi8em+dXsDULIPnFOkMziBFS7ES4MNSG+9b2oSdHYiUCgXMRroSm3nQ54HJANV94Lv0OA4fv87X68pj3lchjyL+FAs4WLad6wPYPvOsEQkpbwWmoC2ZG2B/xPp2s8hq1T2H/sqj8aBg+1qdPlDQM5UQOnisnMmcd5HsXPkmBc0JQVswmW/RYSwRqm91IECgYEA5CRwCGmbvpnSRpzFoKgecztcjbCc1hf6/qsRxt7oICByWsa/jmV1hg5VTg/+nrpWEjb6FN4COLusMtbDxsHFnSWK2OXnBT/GSRKdiTQyNcsTSI4aL7QI7JP2JYp5n5BYtWBSHgoatH6ObnBlhWfepAq3ADrw9nDt8gBLspJtiFECgYEA2YQ71xyYGFL5ms0IcUJZinYhMCueyXGBbdJk8ioXj5rAOtSUfPBg6UdKleP+wB86uCuNrsRoo8qfZyH/X9cuXFHlnO14yUnKy0+Jaz07Fa+8mxQUiU8kw+G1PNXYL4+Qxrdchxi5n0Agorc29lIxr5OD/9Ntjg3AFK1Jpp37aKkCgYEAm6s5PqRA/ycaUdA7EUplrJ7+oje2bGdkfkzgDmCe5vz8ym/+2Pzl2hkBoAhN5qSigj89GOv7fBaigvYEFCS34Ghze1gz8GL5u3aWQ7533Di66bD8sTwZMsQrGqaIIpZt0STuI00lt677JORQgVzEkA43nFKbhKy9z3jgLfK4BmECgYEAjmJ1GXbqSOHn6mFUqW8ZVf3F89ZZ9tuKMe2Ljsnm6mYOd3Q4TyC7D3lPZZb9Z54Jvg0kjcacCLvWZJhopsAg5OXnPDZm3ryjNzs1ZTGLv8Jt38XhO5DHLEJPdSc2gMulpa7ZrsWMnKZJtrngDJX5WElkGs635Ekz91UH5T19GSECgYBfWCMnS8L54i8aB1VGP+/lXWOvQMnSlkN6b2dtdzsxJ+qwbP8l6AaH2Tz6lc3a6B9y5YBTwRdq0EtGXUEPOtNPRAvr9XhlmHK+CEuQRtx6HwbWtq+7158dbo+SvbprbqjgKzmhMGal+nBouCnPuk7n9E1ucyvST3E08dT29jIQKA==';	
		//网关公钥
		$MY_PUBLIC_KEY = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCMpjaWjngB4E3ATh+G1DVAmQnIpiPEFAEDqRfNGAVvvH35yDetqewKi0l7OEceTMN1C6NPym3zStvSoQayjYV+eIcZERkx31KhtFu9clZKgRTyPjdKMIth/wBtPKjL/5+PYalLdomM4ONthrPgnkN4x4R0+D4+EBpXo8gNiAFsNwIDAQAB';
		$msgId = md5(uniqid());
        $request = array(
            "serviceUrl" => 'https://apipcs3.dccnet.com.cn/api/qrcode/V2/query',
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "mer_id"=>"020099161357",
                "cust_id"=>"Ey0LsS39FwdE35TsSW1A2eeoIRMZrjSa",//该字段非必输项
                "out_trade_no"=>"ZHL777O15002039",//该字段非必输项,out_trade_no和order_id选一项上送即可
                "order_id"=>"020004042266201701030000001",//该字段非必输项,out_trade_no和order_id选一项上送即可
            )
        );
        //以下构造函数第1个参数为appid，第2个参数为RSA密钥对中私钥，第6个参数为API平台网关公钥
        $client = new DefaultIcbcClient(
			$APP_ID,
			$MY_PRIVATE_KEY,
			IcbcConstants::$SIGN_TYPE_RSA2,
            '',
            '',
			$MY_PUBLIC_KEY,
            '',
            '',
            '',
            '');
		//执行调用;msgId消息通讯唯一编号，要求每次调用独立生成，APP级唯一
        $resp = $client->execute($request,$msgId,'');
        Log::notice($resp);
        $respObj = json_decode($resp,true);
        if ($respObj["return_code"] == 0) {
            echo $respObj["return_msg"];
        } else {
            echo $respObj["return_msg"];
        }
    }
}