@extends('inheritance')
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
        .content_mark{ width:100%; height:100%; position:fixed; left:0; top:0; z-index:555; background:url(/sd_img/subscribe_background.jpg);background-repeat : no-repeat;background-size: 100%,100%;  display:none;}


    </style>
@endsection
@section('title')
    水丁网大礼包2000元
@endsection
@section('content')
    <input id="subscribe" type="hidden" value="{{$subscribe}}" />
    <div id="container">
    <div style="text-align:center;height:90%; display:block;margin:0 auto;padding: 0 0;background:url(/sd_img/lingqu_background.jpg);background-repeat : no-repeat;background-size: 100%,100%;">


        <div id="lingqubtn" style="padding-top: 135%"><img style="height: 10%;width: 90%;" src="/sd_img/click_shareget.jpg" /></div>
            {{--<span>
                2000礼包
            </span>
        <div id="error-tips" class="error-tips mt10"></div>
        <a href="javascript:void(0);" class="l-btn-login mt20" id="loginbtn" style="border-radius: 3px;">
            点击领取礼包
        </a>--}}
    </div>
    @if(!empty($shareCards))
       {{-- <div class="lipinstate marginTop flex-between b-white" style="padding-top: 10px;padding-left: 20px;padding-right: 10px;">
            <span>暂无分享情况</span>
        </div>--}}
        <div class="lipinstate marginTop flex-between b-white" style="padding-top: 10px;padding-left: 10px;padding-right: 10px;">
           {{-- <ul class="font-14">
                <li >礼品数量</li>
                <li class="font-b color-f53a3a">{{$shareCards->share_num}}</li>
            </ul>
            <ul>
                <li>待领取</li>
                <li class="font-b color-f53a3a">{{$shareCards->share_num - $shareCards->current_num}}</li>
            </ul>
            <ul>
                <li>已领取</li>
                <li class="font-b color-f53a3a">{{$shareCards->current_num}}</li>
            </ul>--}}
            <p style="text-align: center;margin: auto 0px;width: 98%;">
                —————————查看领取记录—————————
            </p>

        </div>
        @if(empty($user_data))
                <div style="padding-top: 10px;padding-left: 10px;padding-right: 10px;">
                    <p style="text-align: center;margin: auto 0px;width: 98%;">
                        暂无领取记录
                    </p>
                </div>
            @else
                <div style="padding-top: 10px;padding-left: 10px;padding-right: 10px;">
                    @foreach($user_data as $val)
                        <div style="vertical-align: bottom;text-align:center ;padding: 0 8px; float:left;width: 90%;">
                            <ul style="float:left;padding: 0 8px; ">
                                @if($val->avatar)
                                    <div style="width:50px; height:50px; border-radius:50%; overflow:hidden;text-align: center;">
                                        <img src="{{$val->avatar}}" width="100%" alt="">
                                    </div>
                                @endif
                            </ul>
                            <ul style="float:left;align-items: center;padding: 20px 8px; ">

                                <li>
                                    <h5 style="text-decoration:none;text-align: center;width:10px;">
                                        <nobr>{{$val->nickname}}</nobr>
                                    </h5>
                                </li>
                                <li>{{$val->get_time}}</li>
                            </ul>
                            <ul style="float:left;align-items:center;padding: 20px 8px; ">
                                <li >已领取</li>
                            </ul>
                        </div>
                    @endforeach
                </div>
        @endif
    @endif
    </div>
    <div class="lanrenzhijia" id="pop-ad">
        <div class="content">
            <img width="270px;" height="332px" src="{{asset('/sd_img/subscribe_share.jpg')}}" usemap="#Map" />
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

        //var link = window.location;
        var title = '水丁网为你准备了2000元送礼礼包，快来领吧!';
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
                desc: '很久没和朋友联系了吧，再好的朋友也要联系！2000元送礼礼包准备好了，去送给朋友吧！', // 分享描述
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



        $('#lingqubtn').on("click", function(){//领取
            if($("#subscribe").val()==0){
                $('.lanrenzhijia').show(0);
                $('.content_mark').show(0);
            }else{
                $('#lingqubtn').unbind('click');//解绑点击事件，防止重复提交
                window.location.href='{{asset('member/getShareCards')}}';
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

