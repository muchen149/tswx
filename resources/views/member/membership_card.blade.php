@extends('inheritance')
@section('title', '会员卡包')
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

    </style>
@endsection

@section('content')
    <body style="background-color: rgb(240,240,240)">
    <input id="subscribe" type="hidden" value="{{$subscribe}}" />
   {{-- <div id="header"></div>--}}
   <div class="font-14 nav">
       <a href="/membership/getCardList/1" @if($use_state == 1) class="tab" @endif><div>未使用</div></a>
       <a href="/membership/getCardList/2" @if($use_state == 2) class="tab" @endif><div>使用中</div></a>
       <a href="/membership/getCardList/-1" @if($use_state == -1) class="tab" @endif><div>已过期</div></a>
   </div>
    @if(!$cardList)
        <div style="margin:0 auto;height:200px;width:200px;margin-top:50px;text-align: center">
            <img style="width:47%" src="{{asset('img/default_list.png')}}">
            <p class="mt20" style="margin-top:10px;font-size:14px;color:rgb(80,80,80);">暂无记录</p>
        </div>
    @else
        <div style="margin-bottom: 85px;">
            @if($use_state != 2)
                @foreach($cardList as $card)
                    <div class="lq-list">
                        <div class="list-img">
                            <img src="@showImg($card->activity_images)">
                        </div>

                        <div class="list-text">
                            <p class="text-title">{{ $card->activity_name }}</p>
                            <p class="text-intro">{{ $card->exp_date }}{{ $card->exp_date_name }} {{ $card->class_name }}
                                服务</p>
                            <p class="text-time">有效时间至:{{ date('Y.m.d', $card->close_time) }}</p>
                        </div>
                        @if($card->use_state == 1)
                            <div class="list-control">
                                {{--<p class="control-title"> ¥ {{ $card->price }}</p>--}}
                                <a href="{{ asset('membership/use/' . $card->membership_id) }}">
                                    <div class="control-btn">立即使用</div>
                                </a>
                            </div>
                        @elseif($card->use_state == -1)
                            <div class="list-control">
                                {{--<p class="control-title"> ¥ {{ $card->price }}</p>--}}
                                <img src="/sd_img/member_overdue.png" alt="" style="width:65px;">
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                @if($use_card && $member->grade > 10)
                <div class="b-white" style="text-align: -webkit-center;margin-top: 10px;">
                    <img src="@showImg($use_card->activity_images)" alt="" width="90%">
                </div>
                <div class="privilege">
                    <div class="list font-14 b-white">
                        <ul class="flex-between border-b">
                            <li class="color-80 font-ber">管家类型</li>
                            <li class="color-80 font-ber">{{$use_card->grade_name}}</li>
                        </ul>
                    </div>

                    <div class="list font-14 b-white">
                        <ul class="flex-between border-b">
                            <li class="color-80 font-ber">服务时间</li>
                            <li class="color-80 font-ber">{{$use_card->exp_date}}日</li>
                        </ul>
                    </div>
                    <a href="{{asset('member/center')}}">
                        <div class="list font-14 b-white">
                            <ul class="flex-between">
                                <li class="color-80 font-ber">立即续费</li>
                                <li class="color-80 font-ber"> > </li>
                            </ul>
                        </div>
                    </a>
                </div>
                <div class="font-12" style="margin-left: 10px;">
                    *您的管家服务将于{{ date('Y-m-d', $use_card->end_time) }}到期
                </div>
                @else
                    <div style="margin:0 auto;height:200px;width:200px;margin-top:50px;text-align: center">
                        <img style="width:47%" src="{{asset('img/default_list.png')}}">
                        <p class="mt20" style="margin-top:10px;font-size:14px;color:rgb(80,80,80);">暂无记录</p>
                    </div>
                @endif
            @endif
        </div>
    @endif
   {{--<div class="bottom-menu">
       <ul>
           <li class=" navBtn"><a href="{{asset('/shop/index')}}"><span class="icon"></span><span class="text">管家服务</span></a></li>
           <li class=" navBtn"><a href="{{asset('/sd_shop/shop')}}"><span class="icon"></span><span class="text">集市</span></a></li>
           <li class="calling navBtn"><a href="{{asset('member/call')}}"><span class="icon"><i></i></span><span class="text">召唤管家</span></a></li>
           <li class=" navBtn"><a href="{{asset('/cart/index')}}"><span class="icon"></span><span class="text">购物车</span></a></li>
           <li class=" navBtn"><a href="{{asset('/personal/index')}}"><span class="icon"></span><span class="text">我的</span></a></li>
       </ul>
   </div>--}}

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



