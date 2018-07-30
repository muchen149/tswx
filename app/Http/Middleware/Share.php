<?php

namespace App\Http\Middleware;

use Closure;
use EasyWeChat\Js\Js;
use Illuminate\Support\Facades\Auth;

class Share
{
    /**
     * 微信分享(初始化jssdk)中间件
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $cur_url = "http://" . $request->server('SERVER_NAME') . $request->server('REQUEST_URI');

        $jssdk = app('wechat')->js;
        $jssdk->config(
            ['onMenuShareTimeline', 'onMenuShareAppMessage'], $debug = false, $beta = false, $json = true
        );

        $signuare=$jssdk->signature($cur_url);

        session(['signPackage' => $signuare]);
        $user = Auth::user();
        if ($user) {
            $cur_url = strpos($cur_url, '?')
                ? $cur_url . '&member=' . $user->member_id
                : $cur_url . '?member=' . $user->member_id;
        }

        session(['wxfx' => [
            'url' => $cur_url,
            'desc' => "水丁商城, 欢迎您的到来!",
            'imgUrl' => 'http://sdwx.shuitine.com/sd_img/zuji.png',
        ]]);
        session(['js' => $jssdk]);

        return $next($request);
    }
}
