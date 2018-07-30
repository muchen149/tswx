@extends('inheritance')

@section('title')
    微信礼品支付成功
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('sd_css/mui.min.css')}}">
    <link rel="stylesheet" href="{{asset('sd_css/common.css')}}">
    <style>
        *{ margin:0; padding:0; list-style:none;}
        img{ border:0;}

        .lanrenzhijia {position: fixed;left: -100%;right:100%;top:0;bottom: 0;text-align: center;font-size: 0; z-index:9999; display:none;}
        .lanrenzhijia:after {content:"";display: inline-block;vertical-align: middle;height: 100%;width: 0;}
        .content{display: inline-block; *display: inline; *zoom:1;	vertical-align: middle;position: relative;right: -100%;}
        .content_mark{ width:100%; height:100%; position:fixed; left:0; top:0; z-index:555; background:#000; opacity:0.5;filter:alpha(opacity=50); display:none;}

        body{
            background: #febc04 url("/sd_img/paySucceed_bg.jpg") no-repeat;
            background-size: 100%;
        }
        .Temporary-link{
            width: 80%;
            text-align: center;
            display: block;
            margin: 60px auto 0;

        }
        .sent-list{
            position: fixed;
            width: 96%;
            height: 210px;
            left: 2%;
            bottom: 25%;
            text-align: center;
        }
        .mui-slider{
            height: 172px!important;
        }
        .mui-slider-item{
            text-align: center;
        }
        .mui-slider .mui-slider-group .mui-slider-item img{
            width: 140px;
        }
        .mui-slider-indicator{
            bottom: 0px;

        }
        .mui-slider-indicator .mui-active.mui-indicator{
            background: #f53a3a;
            box-shadow: none;
        }
        .mui-slider-indicator .mui-indicator{
            box-shadow: none;
        }
    </style>
@endsection

@section('content')
    <body>
    {{--<a href="wxGift_get.html" class="Temporary-link  font-14">A temporary Link to get gifts</a>--}}
    <div class="sent-list">
        <span class="font-16 color-80" style="display:block;margin-top: 10px;margin-bottom:10px;color: #ffffff;font-weight: bold;">我送出的礼物</span>
        <div class="mui-slider" style="height: 220px;">
            <div class="mui-slider-group">
                <div class="mui-slider-item" style="background-color:#ffffff;height:150px;width: 150px;border-radius:50%; overflow:hidden;border:solid rgb(100,100,100) 1px;">
                    <a href=""><img src="{{asset($share_info->sku_image)}}" alt=""></a>

                </div>
            </div>
        </div>
        <p style="color: #ffffff;">{{$share_info->sku_name}}</p>

    </div>
    <div class="lanrenzhijia" id="pop-ad">
        <div class="content">
            <img width="270px;" height="290px" src="{{asset('/sd_img/subscribe_pic.png')}}" usemap="#Map" />
            <map name="Map">
                <area shape="rect" coords="234,5,265,31" href="javascript:hideSubscribe();">
            </map>
        </div>
    </div>
    <div class="content_mark"></div>
</body>

@endsection

@section('js')

    <script src="{{asset('sd_js/jquery.min.js')}}"></script>

    <script src="{{asset('sd_js/font_wvum.js')}}"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        var appId = '{{$signPackage['appId']}}';
        var timestamp = '{{$signPackage['timestamp']}}';
        var nonceStr = '{{$signPackage['nonceStr']}}';
        var signature = '{{$signPackage['signature']}}';

        var link = '{{$share_link}}';
        var title_old = '{{$share_info->gifts_title ? $share_info->gifts_title : "微信分享礼品"}}';
        var imgUrl = '{{$share_info->sku_image}}';
        var nick_name = '{{$nick_name}}';

        var giftsNum='{{$share_info->gifts_num}}';
        var title='';
        if(giftsNum>1){
            title='你的好友'+nick_name+'为大家献上心意礼品，手慢无，快快领取吧！';
        }else{
            title='你的好友'+nick_name+'为你献上心意礼品，快打开看看吧！';
        }



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
                desc: '时间在变，但是我们的情谊永不变，小小礼物，希望你能喜欢！', // 分享描述
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
        function showSubscribe() {
            $('.lanrenzhijia').show(0);
            $('.content_mark').show(0);
        }

        function hideSubscribe(){
            $('.lanrenzhijia').hide(0);
            $('.content_mark').hide(0);
        }



    </script>
    @if($subscribe==0)
        <script>
            showSubscribe();
        </script>
    @endif

@endsection

