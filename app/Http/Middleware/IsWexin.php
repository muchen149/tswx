<?php

namespace App\Http\Middleware;

use App\facades\Api;
use App\models\member\Member;
use Auth;
use Illuminate\Support\Facades\Log;
use Closure;
use CoinRule;

/**
 * 判断用户是否是微信浏览
 *  是  ----> 必须授权
 *  不是 ----> 部分页面可以看，但凡涉及到用户信息的弹出关注微信，然后在微信进入购买或是其他操作
 * @author      :lishuo
 * Class        :IsWeXin
 * @package     :App\Http\Middleware
 */
class IsWexin
{
    /**
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $pid = empty($_GET['member'])?0:$_GET['member'];
        Log::info('邀请者ID'.$pid);
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MicroMessenger/i', $user_agent)) {
            // 微信浏览器，允许访问
            $member_id = $request->cookie('member_id');
            if ($member_id != null) {
                $member = Member::find($member_id);
                if (!$member) {
                    session(['redirect_url' => $request->getRequestUri()]);
                    return redirect('/oauth' . '?member=' . $pid);
                } else {
                    CoinRule::grade($member->member_id);
                    Auth::loginUsingId($member->member_id);
                    return $next($request);                         //如果已经登录，继续进行
                }
            } else {
                session(['redirect_url' => $request->getRequestUri()]);
                return redirect('/oauth' . '?member=' . $pid);                      //未登录的去登录
            }
        } else {
            // 非微信浏览器禁止浏览 ,只有部分页面可以进入，其他页面提示去关注
            //获取url中的参数
            $str = strrchr($request->url(), '/');
            $str = substr($str, 1);

            //允许非微信浏览器进入的页面
            $allow_array = [
                config('path.base_path') . $request->getBaseUrl(),
//                config('path.base_path') . $request->getBaseUrl() . '/shop/index',
                config('path.base_path') . $request->getBaseUrl() . '/shop/product/' . $str,
                //下面2个页面是暂时的，为了平台预览用的
//                config('path.base_path') . $request->getBaseUrl() . '/ynad/index',
                config('path.base_path') . $request->getBaseUrl() . '/ynad/detaile/' . $str,
            ];

            if (in_array($request->url(), $allow_array)) {
                return $next($request);
            } else {
                return \Response::view('errors.concern');
            }
        }
    }
}
