@extends('inheritance')
@section('title', '优惠劵活动')
@section('css')
    {{--<link href="{{asset('css/header.css')}}" rel="stylesheet" type="text/css"/>--}}
    <link rel="stylesheet" href="{{asset('/elife_css/new_common.css')}}">
    <link rel="stylesheet" href="{{asset('/sd_css/new_index.css')}}">
    <style>
        * {
            margin: 0;
            padding: 0;
            list-style: none;
            font-family: arial;
        }

        body {
            background-color: rgb(240, 240, 240);
        }

        .lq-list {
            width: 100%;
            margin: 10px auto 0;
            height: 90px;
            background-color: white;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .lq-list .list-img {
            margin-left: 1%;
            -width: 35%;
            text-align: center;
        }

        .lq-list .list-img img {
            width: 120px;
        }

        .lq-list .list-text {
            -width: 50%;
            margin-left: 2px;
            color: rgb(50, 50, 50);
        }

        .list-text .text-title {
            font-size: 14px;
            font-weight: 800;
        }

        .list-text .text-intro {
            font-size: 12px;
            color:red;
            overflow: hidden;
        }

        .list-text .text-time {
            display: block;
            font-size: 12px;
            line-height: 20px;
            height: 20px;
            margin-bottom: -1%;
        }

        .list-control {
            -width: 25%;
            display: inline-block;
            text-align: center;
        }

        .control-title {
            font-size: 14px;
            color: rgb(80, 80, 80);
            font-weight: 800;
        }

        .control-btn {
            width: 55px;
            margin: 0 auto;
            height: 55px;
            border-radius: 6px;
            background-color:#e42f46 ;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 15px;
            color: white;
            cursor: pointer;
            font-weight: 600;
            font-family: "微软雅黑";
        }
        /*头部导航*/
        .nav {
            width: 100%;
            height: 44px;
            display: flex;
            justify-content: space-around;
            background-color: #ffffff;
        }
        .nav div {
            box-sizing: border-box;
            padding: 10px 0;
            text-align: center;
            font-weight: 600;
        }
        .tab {
            border-bottom: 1px solid #e90006;
        }
        /*.nav a {
            color:#ed4159;
        }*/
        /*已使用页面*/
        .privilege {
            width: 100%;
        }
        .privilege ul {
            width: 95%;
            height: 50px;
            margin: 0px auto;
        }

        .lanrenzhijia {position: fixed;left: -100%;right:100%;top:0;bottom: 0;text-align: center;font-size: 0; z-index:9999; display:none;}
        .lanrenzhijia:after {content:"";display: inline-block;vertical-align: middle;height: 100%;width: 0;}
        .content{display: inline-block; *display: inline; *zoom:1;	vertical-align: middle;position: relative;right: -100%;}
        .content_mark{ width:100%; height:100%; position:fixed; left:0; top:0; z-index:555; background:#000; opacity:0.5;filter:alpha(opacity=50); display:none;}

        .head .nav{
            position: fixed;
            top: 0;
            padding: 0 4px;
            line-height: 38px;
            width:100%;
            z-index: 999999;
            font-family: "微软雅黑";
        }
        .head .nav .return{
            display: inline-block;
            width: 10%;
            margin-left: -40%;
        }
    </style>
@endsection

@section('content')
    <body style="background-color: rgb(240,240,240)">
    {{--<input id="subscribe" type="hidden" value="{{$subscribe}}" />--}}
    {{-- <div id="header"></div>--}}
    {{--<div class="font-14 nav">
        <a href="/membership/getCardList/1" @if($use_state == 1) class="tab" @endif><div>未使用</div></a>
        <a href="/membership/getCardList/2" @if($use_state == 2) class="tab" @endif><div>使用中</div></a>
        <a href="/membership/getCardList/-1" @if($use_state == -1) class="tab" @endif><div>已过期</div></a>
    </div>--}}
    <div class="head">
        <div class="nav b-white">
            <a class="return font-14 color-80" href="{{asset('/elife/eLifeIndex')}}"><img src="{{asset('sd_img/next.png')}}" alt="" class="size-26" style="transform:rotate(180deg);"></a>
            <a class="title font-14 color-80" href="#">优惠劵活动</a>
        </div>
    </div>
        @if(!$couponActivity)
            <div style="margin:0 auto;height:200px;width:200px;margin-top:50px;text-align: center">
                <img style="width:47%" src="{{asset('img/default_list.png')}}">
                <p class="mt20" style="margin-top:10px;font-size:14px;color:rgb(80,80,80);">尽请期待</p>
            </div>
        @else
            <div style="margin-bottom: 85px;margin-top:50px;">
                    @foreach($couponActivity as $active)
                        <div class="lq-list">
                            <div class="list-img">
                                <img src="@showImg($active->activity_images)">
                            </div>

                            <div class="list-text">
                                <p class="text-title">{{ $active->activity_name }}</p>
                                <p class="text-intro">￥:&nbsp;{{$active->price}}</p>
                                <p class="text-time">有效时间至:{{ date('Y.m.d', $active->end_time) }}</p>
                            </div>
                            <div class="list-control" code="{{$active->activity_id}}">
                                {{--<p class="control-title"> ¥ {{ $card->price }}</p>--}}
                                {{--<a href="{{ asset('membership/use/' . $active->membership_id) }}">--}}
                                @if($active->draw == 0)
                                    <div class="control-btn">领取</div>
                                {{--</a>--}}
                                @else
                                    <div class="control-btn" style="background-color:gray">领取</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
            </div>
        @endif
    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('elife_js/common-top.js')}}"></script>
    <script type="text/javascript">
        $('.control-btn').click(function () {
            debugger;
            var url = "/elife/coupon/couponDraw";
            var id = $('.list-control').attr('code');
            $.ajax({
                url: url,
                type:'post',
                data: {'activity_id':id},
                dataType: ''
            }).done(function(res) {
                debugger;
                if (res.code == 0) {
                    message(res.message);
                    $(".control-btn").css("background-color","gray");
                }else{
                    if(res.code == 1){
                        message(res.message);
                    }
                    if(res.code == 2){
                        message(res.message);
                    }
                }
            }).fail(function(e){
                message('系统繁忙，请稍后再试！');
            });
        });
    </script>
@endsection