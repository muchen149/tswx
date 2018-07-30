@extends('inheritance')

@section('title')
    助力英雄榜
@endsection

@section('css')
    <style type="text/css">
        *{
            margin:0;
            padding:0;
            border:0;
        }
        body{margin:0px auto; max-width:640px; min-width:320px;}
        .btdh{
            margin-left:20px;}
        .btdh li{
            width:30%;
            float:left;
            line-height:60px;
            margin-left:10px;
            list-style-type:none;
            font-size:14px;
            font-weight:bold;
        }
        .btdh1{
            margin-left:20px;
            margin-top: 5px;
            margin-bottom: 5px;
            font-size: 14px;
            list-style: none;
            word-break: break-all;}
        .btdh1 li{
            width:30%;
            float:left;
            line-height:60px;
            margin-left:10px;
            list-style-type:none;
            font-size:14px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;

        }
    </style>
@endsection

@section('content')

    <body>
    <div class="onebox">
        <img src="/sd_img/tongjiNeida.jpg" width="100%" alt=""/>
        <div style="width:100%; height:60px; background:#e5e5e5;  " >
            <ul class="btdh">
                <li>微信昵称</li>
                <li>助力盒数(盒)</li>
                <li>助力金额（元）</li>
            </ul>
            @if($list)
            @foreach($list as $item)
                    <ul class="btdh1">
                        <li>{{ $item->member_name }}{{--{{ strlen($item->member_name)>=5?substr($item->member_name,5):$item->member_name }}--}}</li>
                        <li>{{ $item->cnt }}盒</li>
                        <li>{{ $item->total }}元</li>
                    </ul>
            @endforeach
            @endif
        </div>
    </div>
    </body>
@endsection

@section('js')
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
   <script>
       //分享相关数据——start
       var appId = '{{$signPackage['appId']}}';
       var timestamp = '{{$signPackage['timestamp']}}';
       var nonceStr = '{{$signPackage['nonceStr']}}';
       var signature = '{{$signPackage['signature']}}';
       var link = 'http://sdwx.shuitine.com/member/stat';
       var title = '为内大加油喝彩，助力内大篮球队助力榜';


       var imgUrl = '{{asset('sd_img/neidafengxiang.jpg')}}';
       var desc = '一起来看看吧，一起为内大加油喝彩吧！！！';

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
               desc: desc,//'分享送好礼', // 分享描述
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

       //分享相关数据——end
    </script>


@endsection



