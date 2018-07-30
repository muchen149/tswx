{{--@extends('inheritance')--}}
@section('css')
    <link rel="stylesheet" type="text/css" href="/css/reset.css">
    <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <link rel="stylesheet" type="text/css" href="/css/child.css">
    <link rel="stylesheet" type="text/css" href="/css/header.css">
    <style>
        a:link, a:visited, a:hover, a:active {
            text-decoration: none;
        }
    </style>
@endsection
@section('title')
    出错了
@endsection
@section('content')
    <body style="padding-bottom:20px;">
    {{--<div id="header"></div>--}}
    <div class="jf-wrap" id="main-container">
        <div style="margin:0 auto;height:200px;margin-top:40px;text-align: center">
            <img style="width:50%" src="/img/error.png">

            @if(empty($errorData['message']))
                <p class="mt20" style="color:#323232;" id="message"><strong>出错了！</strong></p>
            @else
                <p class="mt20" style="color:red" id="message"><strong style="font-weight: 600;">{{$errorData['message']}}</strong></p>
            @endif

            @if(empty($errorData['url']))
                <input id="redirectUrl" type="hidden" value="{{url('/index')}}">
                <p class="mt10" style="color:#323232;font-size:14px;"><span id="second">5</span>&nbsp;秒后自动跳转，如果没有跳转可<a
                            href="{{url('/index')}}" style="color:#4226e6;text-decoration: underline">点击此处</a>
                </p>
            @else
                <input id="redirectUrl" type="hidden" value="{{$errorData['url']}}">
                <p class="mt10" style="color:#323232;font-size:13px;"><span id="second" style="color:red">5</span>&nbsp;秒后自动跳转，如果没有跳转可<a
                            href="{{$errorData['url']}}" style="color:#4226e6;text-decoration:none;"><br/><span style="color:#3b62e3;border-bottom:1px solid #3b62e3">点击此处</span></a>
                </p>
            @endif
        </div>
    </div>
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script>
        var curCount = 5;
        var InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次

        function SetRemainTime() {
            if (curCount == 0) {
                window.clearInterval(InterValObj);//停止计时器
                window.location.href = $("#redirectUrl").val();
            }
            else {
                curCount--;
                $('#second').html(curCount);
            }
        }
    </script>

@endsection
