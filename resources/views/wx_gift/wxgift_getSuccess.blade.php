@extends('inheritance')

@section('title')
    礼品领取成功
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        body{
            background: #aa8abc url("/sd_img/wxGiftGetSuccess.jpg") no-repeat;
            background-size: 100%;
        }
        .wxGift-succeed-tip{
            margin-top: 40px;
            width: 100%;
            text-align: center;
        }
        .wxGift-succeed-tip img{
            width: 80px;
        }
        .wxGift-succeed-tip p{
            margin-top: 12px;
        }
        .wxGift-succeed-btn-Group{
            margin-top: 80%;
            width: 100%;
            text-align: center;
        }
        .wxGift-succeed-btn-Group .btn{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 80%;
            height: 44px;
            margin-left: 10%;
            border-radius: 6px;
            box-shadow: 1px 1px 2px rgba(50,50,50,.4);
        }
        .wxGift-succeed-btn-Group .btn li:first-child{
            margin-right: 10px;
        }
        .wxGift-succeed-attention img{
            width: 120px;
            height: 120px;
        }
        .wxGift-succeed-attention li:last-child{
            margin-top: 6px;
        }
        .attention-instruction{
            width: 80%;
            margin: 4px auto 36px;
        }
        .wxGift-succeed-send{
            background-color: #fe586f;
            /*background-color: #18a4ff;*/
            margin-bottom: 36px;
        }
        .wxGift-succeed-share{
            background-color: #18a4ff;
            /*background-color: #f9c528;*/
            margin-bottom: 36px;
        }
    </style>
@endsection

@section('content')
    <body>
    {{--<div class="wxGift-succeed-tip">
        <img src="{{asset('sd_img/wxGift-succeed-tip.png')}}" alt="">
        <p class="font-14 color-80">恭喜,礼品领取成功!</p>
    </div>--}}
    <div class="wxGift-succeed-btn-Group">
        <ul class="wxGift-succeed-attention">
            <li><img src="{{asset('sd_img/sd_qrcode.jpg')}}" alt=""></li>
            <li class="font-14 color-50" style="font-size:14px;color: #ffffff;font-weight: bold;">长按关注公众号</li>
        </ul>
        <p class="attention-instruction font-12 color-80" style="font-size:14px;color: #ffffff;font-weight: bold;">关注公众号后,可在订单查看礼品状态</p>
        <ul class="wxGift-succeed-send btn">
            <li> <svg class='icon font-20 color-white' aria-hidden='true' ><use xlink:href='#icon-liwu'></use></svg></li>
            <li class="font-14 color-white"><a style="color: white" href="{{asset('gift/index')}}">我也要送礼</a></li>
        </ul>
        {{--<ul class="wxGift-succeed-share btn">--}}
            {{--<li> <svg class='icon font-20 color-white' aria-hidden='true' ><use xlink:href='#icon-iconziti17'></use></svg></li>--}}
            {{--<li class="font-14 color-white">分享给朋友</li>--}}
        {{--</ul>--}}
    </div>

    </body>

@endsection

@section('js')

    <script src="{{asset('sd_js/jquery.min.js')}}"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        var appId = '{{$signPackage['appId']}}';
        var timestamp = '{{$signPackage['timestamp']}}';
        var nonceStr = '{{$signPackage['nonceStr']}}';
        var signature = '{{$signPackage['signature']}}';

        var link = '{{$share_link}}';
        var title = '{{"微信分享送礼"}}';
        var imgUrl = '{{$share_info->sku_image}}';
        var desc =   '{{$member->nick_name}}'+'领取了一个礼品，你也快来吧！';

        wx.config({
            debug: false,
            appId: appId,
            timestamp: timestamp,
            nonceStr: nonceStr,
            signature: signature,
            jsApiList: [
                // 所有要调用的 API 都要加到这个列表中
                'onMenuShareTimeline',
                'onMenuShareAppMessage'
            ]
        });
        wx.ready(function () {
            // 在这里调用 API
            wx.onMenuShareTimeline({
                title: title, // 分享标题
                imgUrl: imgUrl, // 分享图标
                link:link,
                success: function (msg) {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function (msg) {
                    // 用户取消分享后执行的回调函数
                },
                fail: function () {
                    // 用户取消分享后执行的回调函数
                    alert("分享失败，请稍后再试");
                }
            });


            wx.onMenuShareAppMessage({
                title: title, // 分享标题
                desc: desc, // 分享描述
                imgUrl: imgUrl, // 分享图标
                link:link,
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function (msg) {
                    // 用户确认分享后执行的回调函数
                },
                cancel: function (msg) {
                    // 用户取消分享后执行的回调函数
                },
                fail: function () {
                    // 用户取消分享后执行的回调函数
                    alert("分享失败，请稍后再试");
                }
            });
        });
    </script>

@endsection

