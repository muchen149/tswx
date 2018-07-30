<?php
/**
 * Created by PhpStorm.
 * User: shuo
 * Date: 16-9-8
 * Time: 下午3:05
 */

namespace App\lib;

use App\models\store\StoreWayBill;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Request;

/**
 * 查看物流接口
 * @author      :lishuo
 * Class        :LogInfo
 * @package     :App\lib
 */
class Logistics
{

    /**
     * 查询物流
     * @param $id
     * @return  array
     */
    public function query($id)
    {
        $param=$id;
        $data=[];
        $waybill=StoreWayBill::find($param);  //从仓点运单表中得到运单对象
        $data['transport_type']=$waybill->transport_type;
        if($waybill->transport_type==0){  //第三方快递物流公司
            $shipperCode = $waybill->express_code;//快递公司
            $logisticCode = $waybill->waybill_code;  //快递单号
            $data['express_name']=$waybill->express_name;
            $data['waybill_code']=$logisticCode;
            $res=$this->getOrderTracesByJson($shipperCode,$logisticCode);
            $data['res']=$res->Traces;

            if(empty($res->State) || $res->State == 0){
                $data['State']='无轨迹';
            }elseif($res->State == 1){
                $data['State']='已揽收';
            }elseif($res->State == 2){
                $data['State']='在途中';
            }elseif($res->State == 3){
                $data['State']='签收';
            }elseif($res->State == 4){
                $data['State']='问题件';
            }else{
                $data['State']='到达派件城市';
            }

        }else if($waybill->transport_type==2){  //捎货
            $data['transport_plate_number']=$waybill->transport_plate_number;
            $data['transport_driver_name']=$waybill->transport_driver_name;
            $data['transport_tel_num']=$waybill->transport_tel_num;
        }

        return $data;
    }


    /**
     * Json方式 查询订单物流轨迹
     */
    function getOrderTracesByJson($shipperCode,$logisticCode){
        $requestData= "{'ShipperCode':'".$shipperCode."','LogisticCode':'".$logisticCode."'}";
        $eBusinessID=config('yydwx.eBusinessID');  //电商ID
        $appKey=config('yydwx.appKey'); ////电商加密私钥，快递鸟提供，注意保管，不要泄漏
        $reqURL=config('yydwx.reqUR'); //请求url
        $datas = array(
            'EBusinessID' => $eBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData),
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData,$appKey);
        $result=$this->sendPost($reqURL, $datas);

        //根据公司业务处理返回的信息......
        $res=json_decode($result);
        return $res;
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }


    /**
     * /**
     * 电商Sign签名生成
     * @param $data 内容
     * @param $appkey Appkey
     * @return DataSign签名
     */
    function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

}