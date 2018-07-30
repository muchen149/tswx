<?php

namespace App\controllers\wx;

use App\Http\Controllers\Controller;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WxController extends Controller
{
    /**
     * 微信消息及事件通知入口
     */
    public function serve()
    {
        $wechat = app('wechat');

        $wechat->server->setMessageHandler(function ($message) {
            switch ($message->MsgType) {
                case 'event':
                    if ($message->Event == 'subscribe') {
                        $redpack_con = new RedPackController();
                        $redpack_con->sendRedPackByOpenid($message->FromUserName);
                        $this->wxSendSubscribeMsg($message->FromUserName);
                        return '';
                    }
                    break;

                case 'text':
//                    return '收到文字消息';

                    //如果包含关键字，则作为客服消息转发
                    $keyword = trim($message->Content);
                    if(strstr($keyword, "投诉")||
                        strstr($keyword, "你好") ||
                        strstr($keyword, "在吗")||
                        strstr($keyword, "人工") ||
                        strstr($keyword, "客服")){
                        return new \EasyWeChat\Message\Transfer();
                    }
                    break;

                case 'image':
//                    return '收到图片消息';
                    break;

                case 'voice':
//                    return '收到语音消息';
                    break;

                case 'video':
//                    return '收到视频消息';
                    break;

                case 'location':
//                    return '收到坐标消息';
                    break;

                case 'link':
//                    return '收到链接消息';
                    break;

                // ... 其它消息
                default:
//                    return '收到其它消息';
                    break;
            }

            return '您可以直接在下方留言或拨打我们客服服务热线010-56266744，如需在线客服服务，请输入“人工客服”，我们会尽快为您处理。（工作时间每周一至周五9：00-18:00）';

               /* '小主您好，欢迎选择水丁管家，开启您的精致生活！
我们为您“精选商品,精选服务”！
让您生活的每个细节都很精致！
点击底部“召唤管家”开启您的贵族之旅！
如需帮助请回复文字“人工客服”。';*/
        });

        ob_clean();
        return $wechat->server->serve();
    }

    public function wxSendSubscribeMsg($open_id)
    {
        $app = new Application(config('wechat'));

        $userService = $app->user;
        $user=$userService->get($open_id);
        $nickname=$user['nickname'];
        $subscribe_time=date("Y-m-d H:i",$user['subscribe_time']) ;
        $notice = $app->notice;

        /*boolean setIndustry($industryId1, $industryId2) 修改账号所属行业；
        array getIndustry() 返回所有支持的行业列表，用于做下拉选择行业可视化更新；
        string addTemplate($shortId) 添加模板并获取模板ID；
        collection send($message) 发送模板消息, 返回消息ID；
        array getPrivateTemplates() 获取所有模板列表；
        array deletePrivateTemplate($templateId) 删除指定ID的模板。*/

        //$tt = $notice->getPrivateTemplates();

        $data = array(
            "first" => '小主您好，欢迎选择水丁管家，开启您的精致生活！
我们为您“精选商品,精选服务”！
让您生活的每个细节都很精致！
点击底部“召唤管家”开启您的贵族之旅！
如需帮助请回复文字“人工客服”。',
            "keyword1"=>$nickname,
            "keyword2"=>$subscribe_time,
            "remark" => "水丁网大礼包2000元给你准备好了！快去送给你10个最亲密的朋友吧！点击详情领取！",
        );
        /*{{first.DATA}}
会员昵称：{{keyword1.DATA}}
关注时间：{{keyword2.DATA}}
{{remark.DATA}}*/

        Log::notice("-------send to .....".$open_id);
        $messageId = $notice->send([
            'touser' => $open_id,
            'template_id' => 'oOwBmNcg8QKl8w85eW_J_ahKa49pqjxIY2lqYVdFojQ',  //LZyVa7fWPpG-X1KCrBasBp8yNC_atwVImBVgzttHgs4 --zhengshi
            'url' => 'http://tswx.shuitine.com/member/shareIndex', //http://tswx.shuitine.com/member/shareIndex  --zhengshi
            'data' => $data,
        ]);
        Log::notice("send msg success ....");
        Log::notice("msg content: [".serialize($data)."]");
        //$content=serialize($data);
        //return $messageId;
    }

    /**
     * 设置菜单接口
     * @param Request $request
     */
    public function menu()
    {
        /*$buttons = [
            [
                "type" => "scancode_push",
                "name" => "扫一扫",
                "key" => "scan_push_event",
            ],
            [
                "type" => "view",
                "name" => "水丁商城",
                "url" => "http://tswx.shuitine.com/"
            ],
            [
                "type" => "view",
                "name" => "我的",
                "url" => "http://tswx.shuitine.com/personal/index/"
            ],
            [
                "type" => "view",
                "name" => "召唤管家",
                "url" => "http://tswx.shuitine.com/"
            ],
        ];*/

        $buttons = [
            [
                "type"=>"view",
                "name"=>"精致服务",
                "url"=>"http://ets.shuitine.com"
            ],
            [
                "name"=>"精致双十二",
                "sub_button"=>[
                    [
                        "type"=>"view",
                        "name"=>"有机小香米，每天吃一碗养胃",
                        "url"=>"http://ets.shuitine.com/shop/goods/spuDetail/1201421"
                    ],
                    [
                        "type"=>"view",
                        "name"=>"东北有机稻花香，不配菜一样香",
                        "url"=>"http://ets.shuitine.com/shop/goods/spuDetail/1201335"
                    ],
                    [
                        "type"=>"view",
                        "name"=>"河套麦芯颗粒，天然麦香筋道",
                        "url"=>"http://ets.shuitine.com/shop/goods/spuDetail/1201335"
                    ],
                    [
                        "type"=>"view",
                        "name"=>"早餐来一杯，健康新体验 | 燕麦",
                        "url"=>"http://ets.shuitine.com/shop/goods/spuDetail/1201409"
                    ],
                    [
                        "type"=>"view",
                        "name"=>"吃火锅当然要配好羊肉 | 食花百草羊",
                        "url"=>"http://ets.shuitine.com/shop/goods/spuList/1/10/22/0/0/3"
                    ],

                ],
            ],
            [
                "name"=>"联系我们",
                "sub_button"=>[
                    [
                        "type"=>"click",
                        "name"=>"联系客服",
                        "key"=>"@kefu"
                    ],
                    [
                        "type"=>"view",
                        "name"=>"个人中心",
                        "url"=>"http://ets.shuitine.com/personal/index"
                    ],],
            ],
        ];

        $app=app('wechat');
        $menu=$app->menu;
        $menu->destroy(); // 全部
        return $menu->add($buttons);
    }
}