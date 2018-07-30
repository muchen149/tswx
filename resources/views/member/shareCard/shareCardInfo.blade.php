@extends('inheritance')
@section('title')
    礼物到了！
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
    <input id="subscribe" type="hidden" value="{{$subscribe}}" />
    <input id="share_id" type="hidden" value="{{$share_id}}" />

    <div style="height:100%; display:block;margin:0 auto;padding: 0 0;background: url(/sd_img/get_card_backgrund.jpg);background-repeat : no-repeat;background-size: 100%,100%;">

        <div style="padding-top: 58%;padding-left: 10%;"> <span style="font: '微软雅黑', Arial, Helvetica, sans-serif;font-size: 18px;">{{$shareCards->nickname}}</span>  留言：</div>
        <div id="lingqu" style="padding-top: 60%;text-align: center"><img style="height: 10%;width: 90%;" src="/sd_img/click_shareget.jpg" /></div>
        <div id="guanzhu" style="padding-top: 10px;text-align: center"><img style="height: 20px;width:20px;" src="/sd_img/guznzhu_head.jpg" /><span style="text-decoration: underline;color: #c06427; " >关注水丁网后有水丁大礼包送</span></div>

        {{--     <span>
                分享后领取卡页面
            </span>
        @if($hasGot=="1")
            <span>
                已领过
            </span>
        @else
            <span>
                待领取
            </span>
            <a href="javascript:void(0);" class="l-btn-login mt20" id="lingqu" style="border-radius: 3px;">
                领取并使用
            </a>
        @endif--}}




       {{-- <a href="javascript:void(0);" class="l-btn-login mt20" id="guanzhu" style="border-radius: 3px;display: none;">
            关注水丁网，我也要送礼
        </a>

        <div id="error-tips" class="error-tips mt10"></div>--}}

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

        var title = '水丁网—精致生活的服务者。';
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
        wx.ready(function () {
            // 在这里调用 API
            wx.onMenuShareTimeline({
                title: title, // 分享标题
                imgUrl: imgUrl, // 分享图标
                //link:link,
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


            wx.onMenuShareAppMessage({
                title: title, // 分享标题
                desc: '“吃的放心、住的安心、出行省心”，使我们的服务目标！“品质好、花的少”是我们永远的服务理念！', // 分享描述
                imgUrl: imgUrl, // 分享图标
                //link:link,
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

        function showSubscribe() {
            $('.lanrenzhijia').show(0);
            $('.content_mark').show(0);
        }

        function hideSubscribe(){
            $('.lanrenzhijia').hide(0);
            $('.content_mark').hide(0);
        }


        $('#lingqu').on("click", function(){//领取并使用
            $('#lingqu').unbind('click');//解绑点击事件，防止重复提交
            var share_id = $("#share_id").val();

                $.post( '/member/getshareCard',
                        {
                            "share_id": share_id
                        },
                        function(data){
                            debugger;
                            if (data.code==0) {
                                window.location.href='{{asset('membership/getCardList/1')}}';
                                //$('#guanzhu').display='block';
                                return true;
                            } else {
                                message(data.message);
                                window.location.href='{{asset('index')}}';
                                return false;
                            }
                        });

        });
        /*$('#guanzhu').on("click", function(){//guanzhusongli
            var share_id = $("#share_id").val();
            if($('#subscribe').val()==0){
                showSubscribe();
            }else{
                window.location.href='{{asset('member/shareIndex')}}';
            }
        });*/

    </script>
@endsection

