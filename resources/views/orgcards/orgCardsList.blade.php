@extends('inheritance')
@section('title', '机构卡列表')
@section('css')
    {{--<link href="{{asset('css/header.css')}}" rel="stylesheet" type="text/css"/>--}}
    <link rel="stylesheet" href="{{asset('/sd_css/new_common.css')}}">
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
            width: 70px;
            margin: 0 auto;
            height: 30px;
            border-radius: 6px;
            background-color: #e03e53;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            color: white;
            cursor: pointer;
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

        /*卡片样式*/
        .card-list {
            margin-top: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .czcard-wrap {
            width: 100%;
            /*border-bottom: 1px solid rgb(120, 120, 120);*/
        }

        .czcard {
            width: 98%;
            height: 150px;
            margin: 0px auto;
            border-radius: 8px 8px 8px 8px;
            color: white;
        }



        .czcard-mess {
            height: 75px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .czcard-mess img {
            width: 41px;
            height: 41px;
            border-radius: 41px;
        }

        .czcard-mess p {
            font-size: 14px;
            line-height: 22px;
        }

        .czcard-mess .bold {
            font-weight: 600;
        }


        .czcard-time p {
            font-size: 12px;
            line-height: 34px;
            margin-right: 3%;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            float:right;padding-right:10px;
        }

    </style>
@endsection

@section('content')
    <body style="background-color: rgb(240,240,240)">
    <input id="subscribe" type="hidden" value="{{$subscribe}}" />
    {{-- <div id="header"></div>--}}
    @if(!$cardList)
        <div style="margin:0 auto;height:200px;width:200px;margin-top:50px;text-align: center">
            <img style="width:47%" src="{{asset('img/default_list.png')}}">
            <p class="mt20" style="margin-top:10px;font-size:14px;color:rgb(80,80,80);">暂无记录</p>
        </div>
    @else
        <div style="margin-bottom: 85px;">
                @foreach($cardList as $card)
                    <a href="/orgcards/orgcardInfo/{{$card->id}}">
                    {{--<div class="lq-list">
                        <div class="list-img">
                            <img src="@showImg($card->card_image)">
                        </div>

                        <div class="list-text">
                            <p class="text-title">{{ $card->card_name }}</p>
                        </div>
                    </div>--}}
                        <div class="card-list">
                            <div class="czcard-wrap">
                                <div class="czcard" style="background: url('@showImg($card->card_image)');background-size: 100%;">
                                    <div class="czcard-mess">
                                    </div>
                                    <div style="height:75px;background: url(/orgcard_img/card_back.png);border-radius: 0px 0px 8px 8px;">
                                        <ul class="flex-between" style="padding-top:20px;margin: 10px;;">
                                            <li style="font-size: 18px;color: #2c2c2c;float:right">{{ $card->card_name }}</li>
                                            {{--<li style="font-size: 18px;color: #2c2c2c;float:left">{{ $card->card_count }}折</li>--}}
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
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

    </body>
@endsection

@section('js')
    <script type="text/javascript" src="{{asset('js/common-top.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $('.control-btn').on('click', function () {
                window.location.href = '{{asset('personal/wallet/giftCoupons/choseGiftCouponGoods')}}' + '/' + $(this).attr('coupon_id');
            });
        });

        $('.nav a').on('click',function(){
            $(this).addClass('tab').siblings().removeClass('tab');
        });


        function showSubscribe() {
            $('.lanrenzhijia').show(0);
            $('.content_mark').show(0);
        }

        function hideSubscribe(){
            $('.lanrenzhijia').hide(0);
            $('.content_mark').hide(0);
        }

        if($("#subscribe").val()==0){
            $('.lanrenzhijia').show(0);
            $('.content_mark').show(0);
        }

    </script>
@endsection



