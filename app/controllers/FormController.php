<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/9 0009
 * Time: 下午 5:35
 */

namespace App\controllers;

use App\Http\Controllers\Controller;
use App\models\member\MemberOtherAccount;
use Illuminate\Http\Request;
use App\models\form\Form;
use App\models\form\FormData;
use App\models\member\Member;
use App\models\form\FormEnter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class FormController extends BaseController
{

    public function index($eid)
    {

        $user_data = Db::table('form_data')->join('member','form_data.member_id','=','member.member_id')->
        where('eid',$eid)->select('form_data.display','form_data.content','member.nick_name','member.avatar')->get();
        $users = $user_data->count();

        foreach($user_data as $user){

            if($user->display==1){
                $ct=unserialize($user->content);
                if(array_key_exists('F1',$ct)){
                    $user->displayName=$ct['F1'];
                }else{
                    $user->displayName='----';
                }
            }else{
                $user->displayName=$user->nick_name;
            }

        }
        $form = Db::table('form')->join('form_enter','form.eid','=','form_enter.id')->where('form.eid',$eid)
            ->select('form.fmsg','form.formfields','form.eid','form.FRMNM','form_enter.image_url','form_enter.title','form_enter.content','form_enter.sponsor','form_enter.etime','form_enter.address','form_enter.is_show')->first();

        $form->fmsg = $this->MooHtmlspecialchars($form->fmsg);
        $form->image_url = $this->img_domain.'/'.$form->image_url;



        //微信jsapi
        $signPackage = session('signPackage');
        //微信分享链接
        $stoken = !empty($_GET['stoken']) ? $_GET['stoken'] : '';
        /*
        $request_uri = substr($signPackage['url'], 0, strpos($signPackage['url'], '?'));
        $share_link = $request_uri . '?stoken=' . openssl_encrypt($grade, config('yydwx')['cipher'], config('yydwx')['key'], 0, config('yydwx')['iv']);
        */
        $url = "http://$_SERVER[HTTP_HOST]";
        $share_link = $url . "/form/index/" . $eid;

        return view('form.formview', compact('form','users','user_data','signPackage', 'share_link', 'stoken'));
    }

    public function add(Request $request)
    {
        $member_id=0;
        if (Auth::user()) {
            $member = Auth::user();
            $member_id = $member->member_id;
        } else {
            redirect('/oauth');
        }

        $eid     = $request->input('FRMID');
        $user_id = FormData::where('member_id',$member_id)->where('eid',$eid)->get(['member_id','eid'])->toarray();

        $anonymous=$request->input('anonymous');//获取匿名
        if(!empty($user_id)){
            return view('form.success',compact('user_id','eid'));
        }else{
            if($anonymous == 1){//匿名
                $display = 0;
            }else{
                $display = 1;
            }
            $content = serialize($_POST);
            $user = (string)$_POST['F1'];
            $phone = (int)$_POST['F2'];
            $email = (string)$_POST['F3'];
            $data = [
                'eid'=>$eid,
                'content'=>empty($content) ? '':$content,
                'enter_user'=>empty($_POST['F1']) ? '':$user,
                'enter_phone'=>empty($_POST['F2']) ? '':$phone,
                'enter_email'=>empty($_POST['F3']) ? '':$email,
                'member_id'=>$member_id,
                'display'=>$display,
                'addtime'=>time(),
            ];
            FormData::create($data);
            return view('form.success',compact('user_id','eid'));
        }

        /*return redirect()->action('FormController@index',[$fid]);*/


    }

    /**
     * 为变量或者数组添加转义
     * @param string $value - 字符串或者数组变量
     * @return array
     */
    public function MooAddslashes($value) {
        return $value = is_array($value) ? array_map('MooAddslashes', $value) : addslashes($value);
    }

    /**
     * 将特殊字符转成 HTML 格式。比如<a href='test'>Test</a>转换为&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;
     * @param $value - 字符串或者数组变量
     * @return array
     */
    public function MooHtmlspecialchars($value) {
        return is_array($value) ? array_map('MooHtmlspecialchars', $value) : preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1', str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'),array('&', '"', '<', '>'),$value));
    }

}