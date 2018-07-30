@extends('inheritance')

@section('title')
    邀请好友
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        .inviteFriend-container{
            background: white url("../../sd_img/invitedFriend-bg.jpg") no-repeat;
            background-size: 100% 100%;
            text-align: center;
            overflow: hidden;
        }
        .f1{
            width: 80%;
            margin-left: 10%;
            margin-top: 81%;
        }
        .f1 li:first-child{
            margin-bottom: 10px;
        }
        .f1 li:nth-child(2){
            margin-bottom: 14px;
            line-height: 22px;
        }
        .f1 li:nth-child(3){
            margin-bottom: 15px;
        }
        .f2{
            margin-bottom: 12px;
            color: #f53a3a;
        }
        .f3{
            width: 50%;
            margin-left: 25%;
            margin-bottom: 15px;
        }
        .inviteFriend-btn{
            width: 120px;
            height: 32px;
            margin: 0 auto;
            border: 1px solid rgb(220,220,220);
            border-radius: 6px;
        }
    </style>
@endsection

@section('content')
<body >
<div class="inviteFriend-container">
    <ul class="f1">
        <li class="font-14 color-50 font-ber">邀请好友共享优惠</li>
        <li class="font-12 color-80">将页面分享给好友，好友首次下单后，您将和您的好友同时获得1个月{{$plat_signature}}服务资格</li>
        <li class="font-12 color-50">已经成功邀请<span style="color: #f53a3a"> {{$people_num}} </span>位好友</li>
    </ul>
    <ul class="f2">
        <li class="font-14  font-ber">获得奖励</li>
    </ul>
    <div class="f3 flex-between">
        <ul>
            <li class="font-12 color-80">会员卡(张) <span class="font-14 color-50 font-ber">{{$membercard_total}}</span></li>
            <li class="font-12 color-80">卡余额(元) <span class="font-14 color-50 font-ber">{{$card_balance_total}}</span></li>
        </ul>
        <ul>
            <li class="font-12 color-80">{{$plat_vrb_name}}(个) <span class="font-14 color-50 font-ber">{{$yesb_total}}</span></li>
            <li class="font-12 color-80">&nbsp;&nbsp;&nbsp;零钱(元) <span class="font-14 color-50 font-ber">{{$wallet_total}}</span></li>
        </ul>
    </div>
    <ul class="inviteFriend-btn flex-center">
        <a href="{{asset('personal/index')}}" class="font-12 color-50">去个人中心查看奖励</a>
    </ul>
</div>

</body>
@endsection


@section('js')

    <script src="{{asset('sd_js/jquery.min.js')}}"></script>

    <script>
        var clientwidth=document.documentElement.clientWidth;
        var clientheight=document.documentElement.clientHeight;
        $('.inviteFriend-container').css({'width':clientwidth,'height':clientheight});
    </script>

    <script src="{{asset('sd_js/font_wvum.js')}}"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        var appId = '{{$signPackage['appId']}}';
        var timestamp = '{{$signPackage['timestamp']}}';
        var nonceStr = '{{$signPackage['nonceStr']}}';
        var signature = '{{$signPackage['signature']}}';

        var link = '{{$share_link}}';
        var title = '邀您开启精致生活，共享管家服务。';
        var imgUrl = '{{asset('sd_img/share_Index.png')}}';
        var desc='好友{{$nick_name}}为你送来水丁管家专享服务，点击开启“精致生活”，定制你的生活，尽情享受。';
        var nick_name = '{{$nick_name}}';

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
                desc: desc,//nick_name+'为你送来了TA的专属礼品！', // 分享描述
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



