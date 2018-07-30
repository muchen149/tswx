<!DOCTYPE html>
<html lang="zh-CN" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
    <meta name="x5-fullscreen" content="true">
    <meta name="full-screen" content="yes">

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    @yield('css')

    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/swiper.jquery.min.js')}}"></script>
    <script src="{{asset('js/fastclick.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/common.js')}}"></script>

    {{--swipe微信轮播图所需要的js--}}
    <script type="text/javascript" src="{{asset('js/wxindex/swipe.js')}}"></script>

    <title>
        @yield('title')
    </title>
</head>

<script>
    $(function () {
        FastClick.attach(document.body);
    });
</script>

@yield('content')

@yield('js')

{{--<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>--}}
{{--<script src="{{asset('js/common.js')}}"></script>--}}
{{--<script type="text/javascript">--}}
{{--wx.config({!! session('js')->config(array('onMenuShareTimeline', 'onMenuShareAppMessage','scanQRCode'), false) !!});--}}

{{--wx.ready(function () {--}}

{{--// 分享到朋友圈--}}
{{--wx.onMenuShareTimeline({--}}
{{--title: '水丁分享',--}}
{{--link: '{!! session('wxfx')['url']!!}',--}}
{{--imgUrl: '{!! session('wxfx')['imgUrl'] !!}'--}}
{{--});--}}

{{--// 分享给朋友--}}
{{--wx.onMenuShareAppMessage({--}}
{{--title: '水丁分享',--}}
{{--desc: "{!! session('wxfx')['desc'] !!}",--}}
{{--link: '{!! session('wxfx')['url']!!}',--}}
{{--imgUrl: '{!! session('wxfx')['imgUrl'] !!}',--}}
{{--type: 'link'--}}
{{--});--}}
{{--});--}}
{{--</script>--}}
</html>
