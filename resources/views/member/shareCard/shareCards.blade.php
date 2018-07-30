@extends('inheritance')
@section('title')
    我要送礼啦！
@endsection
@section('css')
    <link rel="stylesheet" type="text/css" href="/css/reset.css">
    <link rel="stylesheet" href="/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/member.css">
    <link rel="stylesheet" type="text/css" href="/css/login.css">

    <style>
        a:link, a:visited, a:hover, a:active{
            text-decoration:none;
        }

        .lanrenzhijia {position: fixed;left: -100%;right:100%;top:0;bottom: 0;text-align: center;font-size: 0; z-index:9999; display:none;}
        .lanrenzhijia:after {content:"";display: inline-block;vertical-align: middle;height: 100%;width: 0;}
        .content{display: inline-block; *display: inline; *zoom:1;	vertical-align: middle;position: relative;right: -100%;}
        .content_mark{ width:100%; height:100%; position:fixed; left:0; top:0; z-index:555; background:#000; opacity:0.5;filter:alpha(opacity=50); display:none;}


    </style>
@endsection

@section('content')
    <div style="height:100%; display:block;margin:0 auto;padding: 0 0;background: url(/sd_img/shareCards_background.jpg);background-repeat : no-repeat;background-size: 100%,100%;">
        {{--<span>
            领取成功待分享页面
        </span>
        @if($hasGot=="1")
            <span>
                已领过
            </span>
        @else
            <span>
                待领取
            </span>
        @endif--}}
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
@endsection

@section('js')
    <script type="text/javascript" src="/js/zepto.min.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/common-top.js"></script>
    <script type="text/javascript" src="/js/simple-plugin.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        var appId = '{{$signPackage['appId']}}';
        var timestamp = '{{$signPackage['timestamp']}}';
        var nonceStr = '{{$signPackage['nonceStr']}}';
        var signature = '{{$signPackage['signature']}}';

        var link = '{{$share_link}}';
        /*var title = '你的好友'+'{{--{{  $shareCards->nickname  }}--}}'+'为你准备了一份精致生活服务的礼包。';*/
        var title = '感恩人生中有你这个朋友相伴！';

        var imgUrl ='{{asset('sd_img/share_head.png')}}';

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
        if('{{$shareCards->share_num}}}'=='{{$shareCards->current_num}}}'){
            wx.hideMenuItems({
                menuList: [
                    "menuItem:share:appMessage",
                    "menuItem:share:timeline",
                    "menuItem:share:qq",
                    "menuItem:share:weiboApp",
                    "menuItem:favorite",
                    "menuItem:share:facebook",
                    "menuItem:share:QZone"
                ] // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
            });

            /*发送给朋友: "menuItem:share:appMessage"
            分享到朋友圈: "menuItem:share:timeline"
            分享到QQ: "menuItem:share:qq"
            分享到Weibo: "menuItem:share:weiboApp"
            收藏: "menuItem:favorite"
            分享到FB: "menuItem:share:facebook"
            分享到 QQ 空间/menuItem:share:QZone*/
        }
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
                desc: '时间在变，但是我们的情谊永不变，一份不一样的礼物送给你，希望你能喜欢！', // 分享描述
                imgUrl: imgUrl, // 分享图标
                link:link,
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function (msg) {
                    // 用户确认分享后执行的回调函数
                    window.location.href='{{asset('index')}}';
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


        $('#loginbtn').on("click", function(){//领取
            alert($("#subscribe").val())
            if($("#subscribe").val()==1){
                $('.lanrenzhijia').show(0);
                $('.content_mark').show(0);
            }
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
@endsection

